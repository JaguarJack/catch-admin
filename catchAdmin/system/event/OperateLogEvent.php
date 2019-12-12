<?php
namespace catchAdmin\system\event;

class OperateLogEvent
{
    protected $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }
}