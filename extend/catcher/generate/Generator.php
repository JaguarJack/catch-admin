<?php
namespace catcher\generate;


use catcher\exceptions\FailedException;
use catcher\generate\factory\Controller;
use catcher\generate\factory\Migration;
use catcher\generate\factory\Model;
use catcher\generate\factory\SQL;

class Generator
{
    public function done($params)
    {
        $params = \json_decode($params['data'], true);

        [$controller, $model] = $this->parseParams($params);

        $message = [];
        if ($params['create_controller']) {
            if ((new Controller)->done($controller)) {
                array_push($message, 'controller created successfully');
            }
        }

        if ($params['create_table']) {
            if ((new SQL)->done($model)) {
                array_push($message, 'table created successfully');
            }
        }

        if ($params['create_model']) {
            if ((new Model)->done($model)) {
                array_push($message, 'model created successfully');
            }
        }

        if ($params['create_migration']) {
            if ((new Migration)->done([$controller['module'], $model['table']])) {
                array_push($message, 'migration created successfully');
            }
        }

        return $message;
    }

    public function preview($type, $params)
    {
        $class = ucfirst($type);

        (new $class)->done($params);
    }


    /**
     * parse params
     *
     * @time 2020年04月28日
     * @param $params
     * @return array[]
     */
    protected function parseParams($params)
    {
        if (!$params['controller']['module'] ?? false) {
            throw new FailedException('请设置模块');
        }

        $controller = [
            'module' => $params['controller']['module'],
            'model'  => $params['controller']['model'] ?? '',
            'controller' => $params['controller']['controller'] ?? '',
            'restful' => $params['controller']['restful'],
            'other_function' => $params['controller']['other_function'],
        ];

        $model = [
            'table' => $params['controller']['table'],
            'model' => $params['controller']['model'],
            'sql'   => $params['model']['data'],
            'extra' => $params['model']['extra'],
        ];


        return [$controller, $model];
    }
}
