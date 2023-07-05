<?php

namespace Modules\User\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Support\Module\ModuleRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Permissions\Models\Departments;
use Modules\User\Models\LogLogin;
use Modules\User\Models\LogOperate;
use Modules\User\Models\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Modules\User\Http\Requests\UserRequest;
use Illuminate\Http\Request;

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
        return $this->user->setBeforeGetList(function ($query){
            if (! $this->getLoginUser()->isSuperAdmin()) {
                $query = $query->where('id', '<>', config('catch.super_admin'));
            }

            if (\request()->has('department_id')) {
                $departmentId = \request()->get('department_id');
                $followDepartmentIds = app(Departments::class)->findFollowDepartments(\request()->get('department_id'));
                $followDepartmentIds[] = $departmentId;
                $query = $query->whereIn('department_id', $followDepartmentIds);
            }

            return $query;
        })->getList();
    }

    /**
     * store
     *
     * @param UserRequest $request
     * @return false|mixed
     */
    public function store(UserRequest $request)
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
        $user = $this->user->firstBy($id)->makeHidden('password');

        if (app(ModuleRepository::class)->enabled('permissions')) {
            $user->setRelations([
                'roles' => $user->roles->pluck('id'),

                'jobs' => $user->jobs->pluck('id')
            ]);
        }

        return $user;
    }

    /**
     * update
     *
     * @param $id
     * @param UserRequest $request
     * @return mixed
     */
    public function update($id, UserRequest $request)
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
        if ($this->user->deleteBy($id)) {
            // 撤销用户的所有令牌
            $this->user->tokens()->delete();
        }

        return true;
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
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loginLog(LogLogin $logLogin)
    {
        $user = $this->getLoginUser();

        return $logLogin->getUserLogBy($user->isSuperAdmin() ? null : $user->email);
    }

    public function operateLog(LogOperate $logOperate, Request $request)
    {
        $scope = $request->get('scope', 'self');

        return $logOperate->setBeforeGetList(function ($builder) use ($scope){
            if ($scope == 'self') {
                return $builder->where('creator_id', $this->getLoginUserId());
            }
            return $builder;
        })->getList();
    }

    /**
     * @return void
     */
    public function export()
    {
        return User::query()
                    ->select('id', 'username', 'email', 'created_at')
                    ->without('roles')
                    ->get()
                    ->download(['id', '昵称', '邮箱', '创建时间']);
    }
}
