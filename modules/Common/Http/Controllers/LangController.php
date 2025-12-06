<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Support\Facades\File;

class LangController
{
    public function translate($lang)
    {
        //return admin_cache('lang_'.$lang, 300, function () use ($lang) {
            $translations = [];
            $files = File::allFiles(lang_path($lang));

            foreach ($files as $file) {
                $translations[$file->getFilenameWithoutExtension()] = require $file->getRealPath();
            }


            return $translations;
       // });
    }
}
