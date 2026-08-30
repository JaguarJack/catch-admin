<?php

namespace Modules\System\Support\Sms;

use Modules\System\Enums\SmsChannel;

class AliYun extends Sms
{
    protected function config(): array
    {
        return config('sms.aliyun');
    }

    protected function gateway(): string
    {
        // TODO: Implement gateway() method.
        return SmsChannel::ALIYUN->value();
    }

    public function send(string $template, string $mobile, array $templateData = []): bool
    {
        $template = $this->getTemplateBy($template);

        $data = [];
        if (count($templateData)) {
            preg_match_all('/\{(.*?)\}/', $template->content, $matches);

            foreach ($matches[1] as $k => $match) {
                $data[$match] = $templateData[$k];
            }
        }

        $this->getGateway()
            ->send($mobile, [
                'template' => $template->template_id,
                'data' => $data,
            ]);

        return true;
    }
}
