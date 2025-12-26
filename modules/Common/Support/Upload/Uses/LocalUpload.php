<?php

namespace Modules\Common\Support\Upload\Uses;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * local upload
 */
class LocalUpload extends Upload
{
    /**
     * upload
     */
    public function upload(): array
    {
        $info = $this->addUrl($this->getUploadPath());
        $info['driver'] = 'local';

        return $info;
    }

    /**
     * app url
     */
    protected function addUrl(array $path): mixed
    {
        $path['path'] = Str::of(
            Storage::disk($this->getDisk())->path($path['path'])
        )->remove(base_path('storage'))->replace('\\', '/')->ltrim('/');

        return $path;
    }

    /**
     * 是否是私有
     *
     * @return bool
     */
    protected function isPrivate(): bool
    {
        return config('filesystems.disks.' . $this->getDisk() . '.visibility') == 'private';
    }

    /**
     * local upload
     */
    protected function localUpload(): string
    {
        $this->checkSize();

        $filename = date('Y-m-d') . '/' . $this->getPath() . '/' . $this->generateName($this->getUploadedFileExt());

        Storage::disk($this->getDisk())->put($filename, $this->file->getContent());

        return $filename;
    }

    protected function getDisk(): mixed
    {
        return Request::get('disk', 'uploads');
    }

    protected function getPath(): mixed
    {
        return Request::get('path', 'attachments');
    }
}
