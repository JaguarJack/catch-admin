<?php
namespace catchAdmin\system\event;

class LoginLogEvent
{
    protected $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }
}