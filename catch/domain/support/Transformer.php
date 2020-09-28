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
    /**
     * 分页展示
     *
     * @param array $data
     * @return mixed
     */
    public static function aliyunDomainPaginate(array $data)
    {
        $list = [];

        foreach ($data['Domains']['Domain'] as $item) {
            $list[] = [
                'name' => $item['DomainName'],
                'created_at' => date('Y-m-d', $item['CreateTimestamp']/1000),
                'dns_server' => $item['DnsServers']['DnsServer'],
                'from' => $item['VersionName'],
                'free' => $item['VersionCode'] === 'mianfei',
                'record_count' => $item['RecordCount'],
                'tags' => $item['Tags']['Tag'],
                'id' => $item['DomainId']
            ];
        }
        
        return Paginator::make($list, $data['PageSize'], $data['PageNumber'], $data['TotalCount']);
    }

    /**
     * 腾讯云域名列表
     *
     * @param array $data
     * @param $page
     * @param $limit
     * @return Paginator|\think\paginator\driver\Bootstrap
     */
    public static function qcloudDomainPaginate(array $data, $page, $limit)
    {
        $info = $data['data']['info'];
        $domains = $data['data']['domains'];

        $list = [];

        foreach ($domains as $item) {
            $list[] = [
                'name' => $item['name'],
                'created_at' => $item['created_on'],
                'dns_server' => [],
                'from' => 'qcloud',
                'free' => $item['grade'] === 'DP_Free',
                'record_count' => $item['records'],
                'tags' => [],
                'id' => $item['id']
            ];
        }

        return Paginator::make($list,  $limit, $page + 1,$info['domain_total']);
    }

    /**
     * 阿里云域名解析
     *
     * @param array $data
     * @return mixed
     */
    public static function aliyunDomainRecordPaginate(array $data)
    {
        $list = [];

        foreach ($data['DomainRecords']['Record'] as &$item) {
            $list[] = array_change_key_case($item);
        }

        return Paginator::make($list, $data['PageSize'], $data['PageNumber'], $data['TotalCount']);
    }

    /**
     * DNS 记录
     *
     * @param array $data
     * @param $page
     * @param $limit
     * @return Paginator|\think\paginator\driver\Bootstrap
     */
    public static function qcloudDomainRecordPaginate(array $data, $page, $limit)
    {
        $list = [];

        foreach ($data['data']['records'] as &$item) {
            $item['status'] = $item['status'] === 'enabled' ? 'ENABLE' : 'DISABLE';
            $item['rr'] = $item['name'];
            $item['recordid'] = $item['id'];
            $list[] = array_change_key_case($item);
        }

        return Paginator::make($list, $limit, $page + 1, $data['data']['info']['record_total']);
    }
}