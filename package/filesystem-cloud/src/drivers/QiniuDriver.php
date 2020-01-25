<?php
namespace jaguarjack\filesystem\cloud\drivers;

use League\Flysystem\AdapterInterface;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use think\filesystem\Driver;

class QiniuDriver extends Driver
{

    protected function createAdapter(): AdapterInterface
    {
        // TODO: Implement createAdapter() method.
        $qiniuConfig = \config('filesystem.disks.qiniu');

        return new QiniuAdapter($qiniuConfig['access_key'], $qiniuConfig['secret_key'], $qiniuConfig['bucket'], $qiniuConfig['domain']);
    }
}