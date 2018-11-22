<?php

namespace app\model;

use think\permissions\traits\hasRoles;

class UserModel extends BaseModel
{
	use hasRoles;

    protected $name = 'users';

	/**
	 * Users List
	 *
	 * @time at 2018年11月14日
	 * @param $params
	 * @return \think\Paginator
	 */
    public function getList($params, $limit = self::LIMIT)
    {
    	if (!count($params)) {
    		return $this->paginate($limit);
	    }


    	if (isset($params['name'])) {
		    $user = $this->whereLike('name', '%'.$params['name'].'%');
	    }
	    if (isset($params['email'])) {
    		$user = $this->whereLike('email', '%'.$params['email'].'%');
	    }

	    return $user->paginate($limit, false, ['query' => request()->param()]);
    }

}