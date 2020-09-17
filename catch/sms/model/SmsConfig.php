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

namespace catchAdmin\sms\model;

use catcher\base\CatchModel as Model;

class SmsConfig extends Model
{
    protected $name = 'sms_config';

    protected $field = [
        'id', // 
		'name', // 运营商名称
		'pid', // 父级ID
		'key', // key
		'value', // value
		'creator_id', // 创建人ID
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];

    public function hasConfig()
    {
        return $this->hasMany(SmsConfig::class, 'pid', 'id');
    }

    /**
     * 保存
     *
     * @time 2020年09月16日
     * @param array $data
     * @return bool|int
     */
    public function storeBy(array $data)
    {
        $config = $this->findByName($data['name']);

        if ($config) {
            unset($data['name']);
            $hasConfig = $config->hasConfig()->select();
            if (empty($hasConfig)) {
                return $this->insertConfig($config->id, $data);
            }
            $this->deleteBy(array_column($hasConfig->toArray(), 'id'), true);
            $this->insertConfig($config->id, $data);
            return true;
        }

        if (parent::storeBy([
            'name' => $data['name']
        ])) {
            unset($data['name']);
            $this->insertConfig($this->id, $data);
            return true;
        }
    }

    /**
     * 新增配置
     *
     * @time 2020年09月16日
     * @param $pid
     * @param $data
     * @return int
     */
    protected function insertConfig($pid, $data)
    {
        $config = [];

        $creatorId = $data['creator_id'];
        unset($data['creator_id']);

        foreach ($data as $k => $v) {
            $config[] = [
                'key' => $k,
                'value' => $v,
                'pid' => $pid,
                'creator_id' => $creatorId,
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }

        return $this->insertAll($config);
    }

    /**
     * 根据 name 查找
     *
     * @time 2020年09月16日
     * @param $name
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|\think\Model|null
     */
    public function findByName($name)
    {
        return $this->where('name', $name)->find();
    }

    /**
     * 查找配置
     *
     * @time 2020年09月16日
     * @param $id
     * @param array|string[] $field
     * @param false $trash
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array|mixed
     */
    public function findBy($id, array $field = ['*'], $trash = false)
    {
        $config = [];

        if (!$this->findByName($id)) {
            return [];
        }

        $this->findByName($id)
             ->hasConfig()
             ->select()
             ->each(function ($item) use (&$config){
                $config[$item['key']] = $item['value'];
             });

        return $config;
    }
}