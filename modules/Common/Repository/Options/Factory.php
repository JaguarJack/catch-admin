<?php

namespace Modules\Common\Repository\Options;

use Exception;
use Illuminate\Support\Str;

class Factory
{
    /**
     * make
     * @param string $optionName
     * @return OptionInterface
     * @throws Exception
     */
    public function make(string $optionName): OptionInterface
    {
        $className = __NAMESPACE__.'\\'.Str::of($optionName)->ucfirst()->toString();

        $class = new $className();

        if (! $class instanceof OptionInterface) {
            throw new Exception('option must be implement [OptionInterface]');
        }

        return $class;
    }
}
