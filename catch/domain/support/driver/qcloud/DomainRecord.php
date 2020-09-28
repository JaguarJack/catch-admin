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
namespace catchAdmin\domain\support\driver\qcloud;

use catchAdmin\domain\support\contract\DomainRecordInterface;
use catchAdmin\domain\support\driver\ApiTrait;
use catchAdmin\domain\support\Transformer;

class DomainRecord implements DomainRecordInterface
{
    use ApiTrait;

    /**
     * 列表
     *
     * @param array $params
     * @return mixed
     */
    public function getList(array $params)
    {
        $data = [
            'Action' => 'RecordList',
            'domain' => $params['name'],
            'offset' => ($params['page'] ?? 1) - 1,
            'length' => $params['limit'] ?? 10,
        ];

        if ($params['rr']) {
            $data['subDomain'] = $params['rr'];
        }

        if ($params['type']) {
            $data['recordType'] = $params['type'];
        }

        // TODO: Implement getList() method.
        return Transformer::qcloudDomainRecordPaginate($this->get($data), $data['offset'], $data['length']);
    }

    /**
     * 新增解析
     *
     * @param array $params
     * @return array
     *
     */
    public function store(array $params)
    {
        // TODO: Implement add() method.
        return $this->get([
            'Action' => 'RecordCreate',
            'domain' => $params['name'],
            'subDomain' => $params['rr'],
            'recordType' => $params['type'],
            'value' => $params['value'],
            'recordLine' => $params['line'],
            'ttl' => $params['ttl'],
        ]);
    }

    /**
     * 删除解析
     *
     * @param $recordId
     * @return array
     */
    public function delete($recordId)
    {
        // TODO: Implement delete() method.
        return $this->get([
            'Action' => 'RecordDelete',
            'recordId' => $recordId
        ]);
    }

    /**
     * 更新解析
     *
     * @param array $params
     * @param $recordId
     * @return array
     */
    public function update($recordId, array $params)
    {
        // TODO: Implement update() method.
        return $this->get([
            'Action' => 'RecordModify',
            'recordId' => $recordId,
            'subDomain' => $params['rr'],
            'recordType' => $params['type'],
            'value' => $params['value'],
            'recordLine' => $params['line'],
            'ttl' => $params['ttl'],
        ]);
    }

    /**
     * 设置状态
     *
     * @param $recordId
     * @param $status
     * @return array
     */
    public function enable($recordId, $status)
    {
        return $this->get([
            'Action' => 'RecordStatus',
            'recordId' => $recordId,
            'Status' => strtolower($status)
        ]);
    }

    public function read(array $params)
    {
        // TODO: Implement read() method.
    }
}