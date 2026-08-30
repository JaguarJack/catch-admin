<?php

namespace Modules\System\Support\Sms;

use Catch\Exceptions\FailedException;
use Modules\System\Models\SystemSmsTemplate;
use Overtrue\EasySms\EasySms;

abstract class Sms
{
    abstract protected function config(): array;

    abstract protected function gateway(): string;

    abstract public function send(string $template, string $mobile, array $templateData = []): bool;

    protected function getGateway(): EasySms
    {
        $config = [
            'timeout' => 5.0,
            'default' => [
                'gateways' => [$this->gateway()],
            ],
            'gateways' => [
                'errorlog' => [
                    'file' => storage_path('logs/sms.log'),
                ],
                $this->gateway() => $this->config(),
            ],
        ];

        return new EasySms($config);
    }

    protected function getTemplateBy(string $identify): SystemSmsTemplate
    {
        return SystemSmsTemplate::where('channel', $this->gateway())
            ->where('identify', $identify)->firstOr('*', function () {
                throw new FailedException('模版未找到，请确认是否添加?');
            });
    }
}
