<?php

namespace Modules\Common\Http\Controllers;

use Catch\Support\Decomposer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * @group 公共模块
 *
 * @subgroup 服务器信息
 *
 * @subgroupDescription CatchAdmin 后台服务器信息
 */
class ServerController
{
    /**
     * 服务器信息
     *
     * @responseField os string 操作系统
     * @responseField php string PHP版本
     * @responseField laravel string Laravel版本
     * @responseField catchadmin string CatchAdmin版本
     * @responseField host string 域名
     * @responseField mysql string MySQL版本
     * @responseField memory_limit string 内存限制
     * @responseField max_execution_time string 最大执行时间
     * @responseField upload_max_filesize string 上传文件大小
     *
     * @param  Request  $request
     * @return array
     */
    public function info()
    {
        return Cache::adminRemember('server_info', 7 * 3600 * 24, function () {
            return Decomposer::getReportJson();
        });
    }
}
