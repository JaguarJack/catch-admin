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
namespace catchAdmin\domain\support\driver\aliyun;

use catchAdmin\domain\support\contract\DomainRecordInterface;
use catchAdmin\domain\support\driver\ApiTrait;

class DomainRecord implements DomainRecordInterface
{
    use ApiTrait;

    public function getList(array $params)
    {
        // TODO: Implement getList() method.
        return $this->get([
            'Action' => 'DescribeDomainRecords',
            'DomainName' => $params['name'],
            'PageNumber' => $params['page'] ?? 1,
            'PageSize' => $params['limit'] ?? 20,
            ''
        ]);
    }

    public function store(array $params)
    {
        // TODO: Implement add() method.
        return $this->get([
            'Action' => 'AddDomainRecord',
            'DomainName' => $params['name'],
            'RR' => $params['record'],
            'Type' => $params['type'],
            'Value' => $params['ip']
        ]);
    }

    public function delete(array $params)
    {
        // TODO: Implement delete() method.
        return $this->get([
            'Action' => 'DeleteDomainRecord',
            'RecordId' => $params['record_id']
        ]);
    }

    public function read(array $params)
    {
        // TODO: Implement info() method.
        return $this->get([
            'Action' => 'DescribeDomainRecord',
            'RecordId' => $params['record_id'],
        ]);
    }

    public function update(array $params)
    {
        // TODO: Implement update() method.
        return $this->get([
            'Action' => 'UpdateDomainRecord',
            'RecordId' => $params['record_id'],
            'RR' => $params['record'],
            'Type' => $params['type'],
            'Value' => $params['ip']
        ]);
    }
}