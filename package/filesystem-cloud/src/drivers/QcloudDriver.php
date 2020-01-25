<?php
namespace jaguarjack\filesystem\cloud\drivers;

use League\Flysystem\AdapterInterface;
use Overtrue\Flysystem\Cos\CosAdapter;
use think\filesystem\Driver;

class QcloudDriver extends Driver
{

    protected function createAdapter(): AdapterInterface
    {
        // TODO: Implement createAdapter() method.
        return new CosAdapter(\config('filesystem.disks.qcloud'));
    }
}