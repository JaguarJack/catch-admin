<?php

namespace Modules\System\Support\Sms;

use Modules\System\Enums\SmsChannel;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Throwable;

class QCloud extends Sms
{
    protected function gateway(): string
    {
        // TODO: Implement gateway() method.
        return SmsChannel::QCLOUD->value();
    }

    public function config(): array
    {
        $config = config('sms.qcloud');
        // 腾讯短信需要 app id 是字符串类型
        $config['sdk_app_id'] = (string) $config['sdk_app_id'];

        return $config;
    }

    /**
     * @throws NoGatewayAvailableException
     * @throws Throwable
     * @throws InvalidArgumentException
     */
    public function send(string $template, string $mobile, array $templateData = []): bool
    {
        $template = $this->getTemplateBy($template);

        $this->getGateway()
            ->send($mobile, [
                'template' => $template->template_id,
                'data' => $templateData,
            ]);

        return true;
    }
}
