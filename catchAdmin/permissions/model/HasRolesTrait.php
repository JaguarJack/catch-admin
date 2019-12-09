<?php
namespace catchAdmin\permissions\model;

trait HasRolesTrait
{
    /**
     *
     * @time 2019年12月08日
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'user_has_roles', 'user_id', 'role_id');
    }

    /**
     *
     * @time 2019年12月08日
     * @param $uid
     * @return mixed
     */
    public function getRoles($uid)
    {
        return $this->findBy($uid)->roles()->get();
    }

    /**
     *
     * @time 2019年12月08日
     * @param $uid
     * @param array $roles
     * @return mixed
     */
    public function attach($uid, array $roles)
    {
        return $this->findBy($uid)->roles()->attach($roles);
    }

    /**
     *
     * @time 2019年12月08日
     * @param $uid
     * @param array $roles
     * @return mixed
     */
    public function detach($uid, array $roles)
    {
        return $this->findBy($uid)->roles()->detach($roles);
    }
}