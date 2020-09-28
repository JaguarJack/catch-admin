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

use catchAdmin\domain\support\contract\DomainActionInterface;
use catchAdmin\domain\support\driver\ApiTrait;
use catchAdmin\domain\support\Transformer;

class Domain implements DomainActionInterface
{
    use ApiTrait;

    public function getList(array $params)
    {
        $offset = ($params['page'] ?? 1) - 1;
        $length = $params['limit'] ?? 10;

        // TODO: Implement getList() method.
       return Transformer::qcloudDomainPaginate($this->get([
            'Action' => 'DomainList',
            'offset' => $offset,
            'length' => $length
        ]), $offset, $length);
    }

    public function store(array $params)
    {
        // TODO: Implement add() method.
    }

    public function delete(array $params)
    {
        // TODO: Implement delete() method.
        return $this->get([
            'Action' => 'DeleteDomain',
            'DomainName' => $params['name'],
        ]);
    }

    public function read($name)
    {
        // TODO: Implement info() method.
        return $this->get([
            'Action' => 'DescribeDomainInfo',
            'DomainName' => $name
        ]);
    }
}