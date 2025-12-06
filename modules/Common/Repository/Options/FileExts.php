<?php

namespace Modules\Common\Repository\Options;

class FileExts implements OptionInterface
{
    public function get(): array
    {
        $extensions = [];

        foreach (
            ['docx', 'pdf', 'txt', 'html', 'zip', 'tar', 'doc', 'css', 'csv', 'ppt', 'xlsx', 'xls', 'xml']

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
