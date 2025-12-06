<?php

namespace Modules\System\Support;

use Catch\Exceptions\FailedException;
use Catch\Support\Oss\Ali;
use Catch\Support\Oss\Cos;
use Catch\Support\Oss\QiNiu;
use Illuminate\Support\Facades\Cache;

class Upload
{
    public function ossToken()
    {
        return Cache::remember('oss_sts_token', 1800, function () {
            $config = config('upload.oss');

            $oss = new Ali(
                $config['access_key'],
                $config['secret_key'],
                $config['bucket'],
                $config['role_arn'],
                'roleArn',
                $config['region'],
                1024 * 1024 * 100
            );

            $token = $oss->token();

            if (empty($token)) {
                throw new FailedException('Oss 获取临时token失败');
            }

            $credentials = $token['Credentials'];
            $credentials['region'] = $config['region'];
            $credentials['bucket'] = $config['bucket'];
            $credentials['endpoint'] = $config['end_point'] ?? '';
            $credentials['cname'] = (bool) ($config['is_cname'] ?? false);
            return $credentials;
        });
    }

    public function cosToken(): array
    {
        $config = config('upload.cos');
        $cos = new Cos($config['secret_id'], $config['secret_key'], $config['bucket'], $config['region'], $config['scheme'], $config['cdn']);

        $token = $cos->token();

        $token['bucket'] = $config['bucket'];
        $token['region'] = $config['region'];
        $token['start_time'] = time();

        return $token;
    }

    public function qiniuToken(string $filename): array
    {
        $config = config('upload.qiniu');
        $qiniu = new QiNiu($config['access_key'], $config['secret_key'], $config['bucket'].':'.$filename);

        return [
            'token' => $qiniu->token(),
            'filename' => $filename,
            'url' => $config['domain'].'/'.$filename,
        ];
    }
}
