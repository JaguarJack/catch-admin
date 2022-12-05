<?php

namespace Modules\Options\Http;

use Exception;
use Modules\Options\Repository\Factory;

class OptionController
{
    /**
     * @param $name
     * @param Factory $factory
     * @return array
     * @throws Exception
     */
    public function index($name, Factory $factory): array
    {
        return $factory->make($name)->get();
    }
}
