<?php

namespace Modules\Common\Repository\Options;

use Catch\CatchAdmin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class FormComponent implements OptionInterface
{
    public function get(): array|Collection
    {
        $dymaic = request()->get('dymaic', 0);
        if ($dymaic && class_exists('\CatchForm\Form')) {
            $components = [];
            foreach (Form::getFormComponents() as $component) {
                $components[] = [
                    'label' => $component,
                    'value' => $component,
                ];
            }

            return $components;
        } else {
            // TODO: Implement get() method.
            $stubDir = CatchAdmin::getModulePath('develop').

                'Support'.DIRECTORY_SEPARATOR.'Generate'.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'vue'.DIRECTORY_SEPARATOR.'formItems';

            $stubs = [];

            foreach (File::allFiles($stubDir) as $file) {
                $stubs[] = [
                    'label' => $file->openFile()->fgets(),
                    'value' => File::name($file),
                ];
            }

            return $stubs;
        }
    }
}
