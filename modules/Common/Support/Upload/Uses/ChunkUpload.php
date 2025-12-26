<?php

namespace Modules\Common\Support\Upload\Uses;

use Catch\Exceptions\FailedException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * 分片上传类
 *
 * 支持大文件分片上传、断点续传、进度管理
 */
class ChunkUpload extends Upload
{
    /**
     * 临时分片存储路径
     */
    protected string $tempPath = 'temp/chunks';

    /**
     * 缓存过期时间（秒）
     */
    protected int $cacheExpiry = 3600;

    /**
     * 请求参数
     */
    protected array $requestParams = [];

    /**
     * 设置请求参数
     */
    public function setParams(array $params): static
    {
        $this->requestParams = $params;
        return $this;
    }

    /**
     * 获取参数值
     */
    protected function getParam(string $key, mixed $default = null): mixed
    {
        return $this->requestParams[$key] ?? $default;
    }

    /**
     * 上传方法 - 兼容父类接口
     * 根据请求参数判断是分片上传还是合并操作
     */
    public function upload(): array
    {
        $action = $this->getParam('action', 'chunk');

        return match($action) {
            'chunk' => $this->uploadChunk(),
            'merge' => $this->mergeChunks(),
            'check' => $this->checkProgress(),
            default => throw new FailedException('不支持的操作类型: ' . $action)
        };
    }

    /**
     * 上传分片
     */
    public function uploadChunk(): array
    {
        $this->validateChunkRequest();

        $fileName = $this->getParam('file_name');
        $fileHash = $this->getParam('file_hash');
        $chunkIndex = (int) $this->getParam('chunk_index');
        $chunkHash = $this->getParam('chunk_hash');
        $totalChunks = (int) $this->getParam('total_chunks');
        $chunkSize = (int) $this->getParam('chunk_size');
        $totalSize = (int) $this->getParam('total_size');

        // 验证分片文件
        if (!$this->file instanceof UploadedFile) {
            throw new FailedException('分片文件不能为空');
        }

        // 验证分片哈希
        $this->validateChunkHash($this->file, $chunkHash, $chunkIndex);

        // 存储分片
        $chunkPath = $this->storeChunk($this->file, $fileHash, $chunkIndex);

        // 更新进度
        $this->updateProgress($fileHash, $chunkIndex, $totalChunks, [
            'file_name' => $fileName,
            'total_size' => $totalSize,
            'chunk_size' => $chunkSize
        ]);

        return [

                'file_hash' => $fileHash,
                'chunk_index' => $chunkIndex,
                'chunk_path' => $chunkPath,
                'uploaded' => true

        ];
    }

    /**
     * 合并分片
     */
    public function mergeChunks(): array
    {
        $fileName = $this->getParam('file_name');
        $fileHash = $this->getParam('file_hash');
        $totalChunks = (int) $this->getParam('total_chunks');
        $totalSize = (int) $this->getParam('total_size');

        if (!$fileName || !$fileHash || !$totalChunks) {
            throw new FailedException('合并参数不完整');
        }

        // 检查所有分片是否都已上传
        if (!$this->allChunksUploaded($fileHash, $totalChunks)) {
            throw new FailedException('分片上传不完整，无法合并文件');
        }

        // 合并文件
        $finalPath = $this->mergeChunkFiles($fileHash, $this->generateName($this->getFileExtension($fileName)), $totalChunks);

        // 验证最终文件
        $this->validateMergedFile($finalPath, $fileHash, $totalSize);

        // 清理临时文件和缓存
        $this->cleanup($fileHash);

        // 构造返回信息
        $info = [
            'path' => $finalPath,
            'ext' => $this->getFileExtension($fileName),
            'type' => $this->getFileMimeType($fileName),
            'size' => $totalSize,
            'original_name' => $fileName,
            'driver' => 'chunk'
        ];

        return $this->addUrl($info);
    }

    /**
     * 检查上传进度
     */
    public function checkProgress(): array
    {
        $fileHash = $this->getParam('file_hash');

        if (!$fileHash) {
            throw new FailedException('文件哈希值不能为空');
        }

        $cacheKey = $this->getProgressCacheKey($fileHash);
        $progress = Cache::get($cacheKey, []);

        if (empty($progress)) {
            return [
                'file_hash' => $fileHash,
                'uploaded_chunks' => 0,
                'total_chunks' => 0,
                'percentage' => 0,
                'status' => 'not_started'
            ];
        }

        $uploadedChunks = count($progress['chunks'] ?? []);
        $totalChunks = $progress['total_chunks'] ?? 0;
        $percentage = $totalChunks > 0 ? round(($uploadedChunks / $totalChunks) * 100, 2) : 0;

        return [
            'file_hash' => $fileHash,
            'file_name' => $progress['file_name'] ?? '',
            'uploaded_chunks' => $uploadedChunks,
            'total_chunks' => $totalChunks,
            'percentage' => $percentage,
            'total_size' => $progress['total_size'] ?? 0,
            'status' => $percentage == 100 ? 'completed' : 'uploading'
        ];
    }

    /**
     * 验证分片上传请求参数
     */
    protected function validateChunkRequest(): void
    {
        $required = ['file_name', 'file_hash', 'chunk_index', 'chunk_hash', 'total_chunks', 'chunk_size', 'total_size'];

        foreach ($required as $param) {
            if (!isset($this->requestParams[$param])) {
                throw new FailedException("缺少必需参数: {$param}");
            }
        }

        // 验证哈希格式
        $fileHash = $this->getParam('file_hash');
        if (strlen($fileHash) !== 64) {
            throw new FailedException('文件哈希格式错误，应为64位SHA-256');
        }

        // 验证分片索引
        $chunkIndex = (int) $this->getParam('chunk_index');
        $totalChunks = (int) $this->getParam('total_chunks');

        if ($chunkIndex < 0 || $chunkIndex >= $totalChunks) {
            throw new FailedException('分片索引超出范围');
        }
    }

    /**
     * 验证分片哈希
     */
    protected function validateChunkHash(UploadedFile $chunkFile, string $expectedHash, int $chunkIndex): void
    {
        $chunkContent = file_get_contents($chunkFile->getPathname());

        // 检查哈希格式，支持多种格式：
        // 1. "索引-哈希" 格式 (如: "0-abc123...")
        // 2. 直接的哈希值 (如: "abc123...")
        if (str_contains($expectedHash, '-')) {
            // 格式1: "索引-哈希"，提取哈希部分
            $hashParts = explode('-', $expectedHash, 2);
            if (count($hashParts) === 2) {
                $expectedHashValue = $hashParts[1];
            } else {
                throw new FailedException("分片哈希格式错误: {$expectedHash}");
            }
        } else {
            // 格式2: 直接哈希值
            $expectedHashValue = $expectedHash;
        }

        // 根据哈希长度判断算法类型
        $hashLength = strlen($expectedHashValue);
        $actualHash = match($hashLength) {
            40 => sha1($chunkContent),          // SHA1
            64 => hash('sha256', $chunkContent), // SHA256
            default => md5($chunkContent)       // 默认使用MD5
        };

        if ($actualHash !== $expectedHashValue) {
            // 提供更详细的调试信息
            $debugInfo = [
                'expected_hash' => $expectedHashValue,
                'actual_hash' => $actualHash,
                'hash_algorithm' => match($hashLength) {
                    40 => 'SHA1',
                    64 => 'SHA256',
                    default => 'MD5'
                },
                'chunk_index' => $chunkIndex,
                'chunk_size' => strlen($chunkContent)
            ];

            throw new FailedException("分片 {$chunkIndex} 哈希验证失败。调试信息: " . json_encode($debugInfo));
        }
    }

    /**
     * 存储分片文件
     */
    protected function storeChunk(UploadedFile $chunkFile, string $fileHash, int $chunkIndex): string
    {
        $chunkPath = "{$this->tempPath}/{$fileHash}/chunk_{$chunkIndex}";

        try {
            $content = file_get_contents($chunkFile->getPathname());
            Storage::disk($this->getDisk())->put($chunkPath, $content);

            return $chunkPath;
        } catch (\Exception $e) {
            throw new FailedException("分片存储失败: " . $e->getMessage());
        }
    }

    /**
     * 更新上传进度
     */
    protected function updateProgress(string $fileHash, int $chunkIndex, int $totalChunks, array $metadata = []): void
    {
        $cacheKey = $this->getProgressCacheKey($fileHash);

        $progress = Cache::get($cacheKey, [
            'total_chunks' => $totalChunks,
            'chunks' => [],
            'file_name' => $metadata['file_name'] ?? '',
            'total_size' => $metadata['total_size'] ?? 0,
            'chunk_size' => $metadata['chunk_size'] ?? 0,
            'start_time' => now()->timestamp
        ]);

        $progress['chunks'][$chunkIndex] = [
            'index' => $chunkIndex,
            'uploaded_at' => now()->timestamp,
            'status' => 'uploaded'
        ];

        Cache::put($cacheKey, $progress, $this->cacheExpiry);
    }

    /**
     * 检查所有分片是否已上传
     */
    protected function allChunksUploaded(string $fileHash, int $totalChunks): bool
    {
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = "{$this->tempPath}/{$fileHash}/chunk_{$i}";
            if (!Storage::disk($this->getDisk())->exists($chunkPath)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 合并分片文件 - 使用Laravel内置流式处理
     */
    protected function mergeChunkFiles(string $fileHash, string $fileName, int $totalChunks): string
    {
        $finalPath = $this->generateFinalPath($fileName, $fileHash);
        $disk = Storage::disk($this->getDisk());

        // 创建临时合并文件流
        $tempMergeFile = tempnam(sys_get_temp_dir(), 'laravel_chunk_merge_');

        try {
            // 使用流式方式合并所有分片
            $this->streamMergeChunks($fileHash, $totalChunks, $tempMergeFile);

            // 使用Laravel的putFileAs方法进行流式上传
            // 这个方法会自动处理流式传输，避免内存问题
            $file = new \Illuminate\Http\File($tempMergeFile);
            $storedPath = $disk->putFileAs(
                dirname($finalPath),
                $file,
                basename($finalPath)
            );

            if (!$storedPath) {
                throw new FailedException('存储合并文件失败');
            }

            return $finalPath;

        } catch (\Exception $e) {
            throw new FailedException('文件合并失败: ' . $e->getMessage());
        } finally {
            // 清理临时文件
            if (file_exists($tempMergeFile)) {
                @unlink($tempMergeFile);
            }
        }
    }

    /**
     * 流式合并分片到临时文件
     */
    protected function streamMergeChunks(string $fileHash, int $totalChunks, string $tempFile): void
    {
        $disk = Storage::disk($this->getDisk());

        $tempHandle = fopen($tempFile, 'wb');
        if (!$tempHandle) {
            throw new FailedException('无法创建临时合并文件');
        }

        try {
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = "{$this->tempPath}/{$fileHash}/chunk_{$i}";

                // 检查分片是否存在
                if (!$disk->exists($chunkPath)) {
                    throw new FailedException("分片 {$i} 不存在");
                }

                // 使用Laravel的流式读取
                $chunkStream = $disk->readStream($chunkPath);
                if (!$chunkStream) {
                    throw new FailedException("无法读取分片 {$i}");
                }

                // 流式复制分片内容
                if (stream_copy_to_stream($chunkStream, $tempHandle) === false) {
                    fclose($chunkStream);
                    throw new FailedException("复制分片 {$i} 失败");
                }

                fclose($chunkStream);
            }

        } finally {
            fclose($tempHandle);
        }
    }

    /**
     * 生成最终文件路径
     */
    protected function generateFinalPath(string $fileName, string $fileHash): string
    {
        $path = $this->getPath();
        $ext = $this->getFileExtension($fileName);
        $datePath = date('Y-m-d');

        // 使用文件哈希和原始文件名生成唯一文件名
        $finalName = substr($fileHash, 0, 8) . '_' . $fileName;

        return "{$datePath}/{$path}/{$finalName}";
    }

    /**
     * 验证合并后的文件 - 使用Laravel内置方法
     */
    protected function validateMergedFile(string $filePath, string $expectedHash, int $expectedSize): void
    {
        $disk = Storage::disk($this->getDisk());

        // 使用Laravel的exists方法检查文件
        if (!$disk->exists($filePath)) {
            throw new FailedException('文件合并失败，最终文件不存在');
        }

        // 使用Laravel的size方法验证文件大小
        $actualSize = $disk->size($filePath);
        if ($actualSize !== $expectedSize) {
            $disk->delete($filePath);
            throw new FailedException("文件大小验证失败，期望: {$expectedSize}, 实际: {$actualSize}");
        }

        // 使用Laravel的流式哈希计算
        $actualHash = $this->calculateFileHashStreamLaravel($disk, $filePath);
        if ($actualHash !== $expectedHash) {
            $disk->delete($filePath);
            throw new FailedException('文件哈希验证失败，文件可能损坏');
        }
    }

    /**
     * 使用Laravel Storage的流式哈希计算
     */
    protected function calculateFileHashStreamLaravel($disk, string $filePath, string $algorithm = 'sha256'): string
    {
        // 使用Laravel的readStream方法获取文件流
        $stream = $disk->readStream($filePath);
        if (!$stream) {
            throw new FailedException('无法打开文件流进行哈希计算');
        }

        $hashContext = hash_init($algorithm);

        try {
            // 使用流式读取，每次64KB
            while (!feof($stream)) {
                $buffer = fread($stream, 65536); // 64KB缓冲区
                if ($buffer === false) {
                    break;
                }
                hash_update($hashContext, $buffer);
            }

            return hash_final($hashContext);

        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * 流式计算文件哈希值 - 保留原方法作为备用
     */
    protected function calculateFileHashStream(string $filePath, string $algorithm = 'sha256'): string
    {
        $handle = fopen($filePath, 'rb');
        if (!$handle) {
            throw new FailedException('无法打开文件进行哈希计算');
        }

        $hashContext = hash_init($algorithm);

        try {
            // 分块读取文件，每次64KB
            while (!feof($handle)) {
                $buffer = fread($handle, 65536); // 64KB缓冲区
                if ($buffer === false) {
                    throw new FailedException('读取文件进行哈希计算失败');
                }
                hash_update($hashContext, $buffer);
            }

            return hash_final($hashContext);

        } finally {
            fclose($handle);
        }
    }

    /**
     * 清理临时文件和缓存
     */
    protected function cleanup(string $fileHash): void
    {
        $disk = Storage::disk($this->getDisk());

        // 直接删除整个临时目录（包含所有分片文件）
        $disk->deleteDirectory("{$this->tempPath}/{$fileHash}");

        // 清理缓存
        Cache::forget($this->getProgressCacheKey($fileHash));
    }


    /**
     * 清理过期的分片文件
     *
     * @return void
     */
    public function cleanupExpiredChunks(): void
    {
        $disk = Storage::disk($this->getDisk());

        $chunkDirectories = $disk->directories($this->tempPath);

        if (empty($chunkDirectories)) {
            return;
        }

        foreach ($chunkDirectories as $directory) {
            $fileHash = basename($directory);
            if (! Cache::has($this->getProgressCacheKey($fileHash))) {
                $this->cleanup($fileHash);
            }
        }
    }

    /**
     * 获取进度缓存键
     */
    protected function getProgressCacheKey(string $fileHash): string
    {
        return "chunk_upload:progress:{$fileHash}";
    }

    /**
     * 获取存储磁盘
     */
    protected function getDisk(): string
    {
        return $this->getParam('disk', 'uploads');
    }

    /**
     * 获取存储路径
     */
    protected function getPath(): string
    {
        return $this->getParam('path', 'attachments');
    }

    /**
     * 获取文件扩展名
     */
    protected function getFileExtension(string $fileName): string
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    /**
     * 获取文件MIME类型
     */
    protected function getFileMimeType(string $fileName): string
    {
        $ext = $this->getFileExtension($fileName);
        $imageExts = config('common.upload.image.ext', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);

        return in_array($ext, $imageExts) ? 'image' : 'file';
    }

    /**
     * 添加URL信息
     */
    protected function addUrl(array $info): array
    {
        $info['path'] = Str::of(
            Storage::disk($this->getDisk())->path($info['path'])
        )->remove(base_path('storage'))->replace('\\', '/')->ltrim('/');

        return $info;
    }

    /**
     * 分片上传专用方法 - 重写父类方法
     */
    protected function chunkUpload(): string
    {
        // 这个方法由于架构需要存在，但实际逻辑在upload()方法中处理
        return '';
    }

    /**
     * 重写父类的文件扩展名获取方法
     */
    protected function getUploadedFileExt(): string
    {
        if ($this->file instanceof UploadedFile) {
            return parent::getUploadedFileExt();
        }

        // 对于分片上传，从请求参数获取文件名
        $fileName = $this->getParam('file_name', '');
        return $this->getFileExtension($fileName);
    }

    /**
     * 重写父类的文件大小获取方法
     */
    protected function getUploadedFileSize(): int
    {
        if ($this->file instanceof UploadedFile) {
            return parent::getUploadedFileSize();
        }

        // 对于分片上传，从请求参数获取总大小
        return (int) $this->getParam('total_size', 0);
    }

    /**
     * 重写父类的MIME类型获取方法
     */
    protected function getUploadedFileMimeType(): string
    {
        if ($this->file instanceof UploadedFile) {
            return parent::getUploadedFileMimeType();
        }

        // 对于分片上传，根据文件名判断类型
        $fileName = $this->getParam('file_name', '');
        return $this->getFileMimeType($fileName);
    }

    /**
     * 重写父类的原始文件名获取方法
     */
    public function getOriginName(): string
    {
        if ($this->file instanceof UploadedFile) {
            return parent::getOriginName();
        }

        // 对于分片上传，从请求参数获取原始文件名
        return $this->getParam('file_name', '');
    }
}
