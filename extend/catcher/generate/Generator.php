<?php
namespace JaguarJack\Generator;

use catcher\CatchAdmin;
use JaguarJack\Generator\Factory\Controller;
use JaguarJack\Generator\Factory\SQl;

class Generator
{
    public function done($params)
    {
        $params = \json_decode($params['data'], true);

        //var_dump((new Controller())->generate($params['controller']));die;

        (new Controller())->done($params['controller']);
    }

    public function preview($type, $params)
    {
        $class = ucfirst($type);

        (new $class)->done();
    }
}
