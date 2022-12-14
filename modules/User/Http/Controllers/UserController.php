<?php

namespace Modules\User\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Modules\User\Models\LogLogin;
use Modules\User\Models\User;

class UserController extends Controller
{
    public function __construct(
        protected readonly User $user
    ) {
    }

    /**
     * get list
     *
     * @return mixed
     */
    public function index()
    {
        return $this->user->getList();
    }

    /**
     * store
     *
     * @param Request $request
     * @return false|mixed
     */
    public function store(Request $request)
    {
        return $this->user->storeBy($request->all());
    }

    /**
     * show
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->user->setAfterFirstBy(function (User $user){
            $relations = array_keys($user->getRelations());
            if (in_array('roles', $relations)) {
                $user->setRelations([
                    'roles' => $user->roles->pluck('id'),

                    'jobs' => $user->jobs->pluck('id')
                ]);
            }

            return $user;
        })->firstBy($id)->makeHidden('password');
    }

    /**
     * update
     *
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        return $this->user->updateBy($id, $request->all());
    }

    /**
     * destroy
     *
     * @param $id
     * @return bool|null
     */
    public function destroy($id)
    {
        return $this->user->deleteBy($id);
    }

    /**
     * enable
     *
     * @param $id
     * @return bool
     */
    public function enable($id)
    {
        return $this->user->toggleBy($id);
    }

    /**
     *  online user
     *
     * @return Authenticatable
     */
    public function online(Request $request)
    {
        /* @var User $user */
        $user = $this->getLoginUser()->withPermissions();

        if ($request->isMethod('post')) {
            return $user->updateBy($user->id, $request->all());
        }

        return $user;
    }


    /**
     * login log
     * @param LogLogin $logLogin
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function loginLog(LogLogin $logLogin)
    {
        return $logLogin->getUserLogBy($this->getLoginUser()->email);
    }
}
