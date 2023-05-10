<?php

namespace Modules\Common\Support\Upload\Uses;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalUpload extends Upload
{
    /**
     * upload
     *
     * @return array
     */
    public function upload(): array
    {
        return $this->addUrl($this->getUploadPath());
    }

    /**
     * app url
     *
     * @param $path
     * @return mixed
     */
    protected function addUrl($path): mixed
    {
        $path['path'] = config('app.url') . '/'.

                        Str::of($path['path'])->replace('\\', '/')->toString();

        return $path;
    }


    /**
     * local upload
     *
     * @return string
     */
    protected function localUpload(): string
    {
        $this->checkSize();

        $storePath = 'uploads' . DIRECTORY_SEPARATOR . $this->getUploadedFileMimeType() . DIRECTORY_SEPARATOR . date('Y-m-d', time());

        $filename = $this->generateImageName($this->getUploadedFileExt());

        Storage::build([
            'driver' => 'local',
            'root' => $storePath
        ])->put($filename, $this->file->getContent());

        return $storePath . DIRECTORY_SEPARATOR . $filename;
    }
}
