<?php

namespace Modules\Common\Repository\Options;

class ImageExts implements OptionInterface
{
    public function get(): array
    {
        $extensions = [];

        foreach (
            ['jpeg', 'jpg', 'gif', 'png', 'svg', 'ico', 'doc', 'psd', 'bmp', 'tiff', 'webp', 'tif', 'pjpeg']
            as $value
        ) {
            $extensions[] = [
                'label' => $value,
                'value' => $value
            ];
        }

        return $extensions;
    }
}
