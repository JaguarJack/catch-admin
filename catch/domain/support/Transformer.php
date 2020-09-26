<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catchAdmin\domain\support;

use think\Paginator;

class Transformer
{
    public static function aliyunDomainPaginate($data)
    {
        $list = [];

        foreach ($data['Domains']['Domain'] as $item) {
            $list[] = [
                'name' => $item['DomainName'],
                'created_at' => date('Y-m-d', $item['CreateTimestamp']/1000),
                'dns_server' => $item['DnsServers']['DnsServer'],
                'from' => $item['VersionName'],
                'expired_at' => substr($item['InstanceEndTime'], 0, 10),
                'record_count' => $item['RecordCount'],
                'registrant_email' => $item['RegistrantEmail'],
                'tags' => $item['Tags']['Tag'],
                'id' => $item['DomainId']
            ];
        }
        var_dump($list);
        return Paginator::make($list, $data['PageSize'], $data['PageNumber'], $data['TotalCount']);
    }

    public static function aliyunDomainRecordPaginate($data)
    {
        $list = [];

        foreach ($data['Domains']['Domain'] as $item) {
            $list[] = [
                'name' => $item['DomainName'],
                'created_at' => date('Y-m-d', $item['CreateTimestamp']/1000),
                'dns_server' => $item['DnsServers']['DnsServer'],
                'from' => $item['VersionName'],
                'tags' => $item['Tags']['Tag'],
            ];
        }

        return Paginator::make($list, $data['PageSize'], $data['PageNumber'], $data['TotalCount']);
    }
}