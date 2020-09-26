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

use catchAdmin\domain\support\contract\DomainActionInterface;
use catchAdmin\domain\support\driver\ApiTrait;
use catchAdmin\domain\support\Transformer;

class Domain implements DomainActionInterface
{
    use ApiTrait;

    public function getList(array $params)
    {
        // TODO: Implement getList() method.
       return Transformer::aliyunDomainPaginate($this->get([
            'Action' => 'DescribeDomains',
            'StarMark' => true,
            'SearchModel' => 'LIKE',
            'PageNumber' => $params['page'] ?? 1,
            'PageSize' => $params['limit'] ?? 20,
        ]));
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

    public function read(array $params)
    {
        // TODO: Implement info() method.
        return $this->get([
            'Action' => 'DescribeDomainInfo',
            'DomainName' => $params['name']
        ]);
    }
}