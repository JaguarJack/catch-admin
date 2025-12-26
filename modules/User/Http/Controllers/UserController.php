<?php

namespace Modules\User\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Support\Module\ModuleRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Permissions\Models\Departments;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Import\User as UserImport;
use Modules\User\Models\LogLogin;
use Modules\User\Models\LogOperate;
use Modules\User\Models\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @group 用户模块
 *
 * @subgroup 用户管理
 *
 * @subgroupDescription CatchAdmin 后台用户管理
 */
class UserController extends Controller
{
    public function __construct(
        protected readonly User $user
    ) {
    }

    /**
     * 用户列表
     *
     * @queryParam department_id int 部门
     * @queryParam page int 页码
     * @queryParam limit int 每页条数
     * @queryParam username string 关键字
     * @queryParam email string 邮箱
     * @queryParam status int 状态
     *
     * @responseField code int 状态码
     * @responseField message string 提示信息
     * @responseField page int 当前页
     * @responseField total int 总数
     * @responseField limit int 每页数量
     * @responseField data object[] 数据
     * @responseField data[].id int 用户ID
     * @responseField data[].username string 用户名
     * @responseField data[].avatar string 头像
     * @responseField data[].email string 邮箱
     * @responseField data[].mobile string 手机号
     * @responseField data[].department_id int 部门
     * @responseField data[].roles object[] 角色
     * @responseField data[].jobs object[] 职位
     * @responseField data[].status int 状态
     * @responseField data[].created_at string 创建时间
     * @responseField data[].updated_at string 更新时间
     *
     * @return mixed
     */
    public function index()
    {
        return $this->user->setBeforeGetList(function ($query) {
            if (! $this->getLoginUser()->isSuperAdmin()) {
                $superAdminId = config('catch.super_admin');
                if (! is_array($superAdminId)) {
                    $superAdminId = [$superAdminId];
                }
                $query = $query->whereNotIn('id', $superAdminId);
            }
            if ($departmentId = \request()->get('department_id')) {
                $followDepartmentIds = app(Departments::class)->findFollowDepartments($departmentId);
                $followDepartmentIds[] = $departmentId;
                $query = $query->whereIn('department_id', $followDepartmentIds);
            }

            return $query;
        })->getList();
    }

    /**
     * 新增用户
     *
     * @bodyParam username string required 用户名
     * @bodyParam password string required 密码
     * @bodyParam email string 邮箱
     * @bodyParam mobile string 手机号
     * @bodyParam department_id int 部门
     * @bodyParam roles integer[] 角色
     * @bodyParam jobs integer[] 职位
     *
     * @responseField data int 新增ID
     *
     * @return false|mixed
     */
    public function store(UserRequest $request)
    {
        return $this->user->storeBy($request->all());
    }

    /**
     * 查询用户
     *
     * @responseField id int 用户ID
     * @responseField username string 用户名
     * @responseField avatar string 头像
     * @responseField email string 邮箱
     * @responseField mobile string 手机号
     * @responseField department_id int 部门ID
     * @responseField roles integer[] 角色
     * @responseField jobs integer[] 职位
     * @responseField permissions integer[] 权限
     * @responseField created_at string 创建时间
     */
    public function show($id): mixed
    {
        /* @var User $user */
        $user = $this->user->firstBy($id)->makeHidden('password');

        if (app(ModuleRepository::class)->enabled('permissions')) {
            $user->setRelations([
                'roles' => $user->roles->pluck('id'),

                'jobs' => $user->jobs->pluck('id'),
            ]);
        }

        return $user;
    }

    /**
     * 更新用户
     *
     * @urlParam id int required 用户ID
     *
     * @bodyParam username string required 用户名
     * @bodyParam password string 密码
     * @bodyParam email string 邮箱
     * @bodyParam mobile string 手机号
     * @bodyParam department_id int 部门
     * @bodyParam roles integer[] 角色
     * @bodyParam jobs integer[] 职位
     *
     * @responseField data bool 是否更新成功
     */
    public function update($id, UserRequest $request): mixed
    {
        return $this->user->updateBy($id, $request->all());
    }

    /**
     * 删除用户
     *
     * @responseField data bool 是否更新成功
     *
     * @return bool|null
     */
    public function destroy($id)
    {
        return $this->user->deletesBy($id);
    }

    /**
     * 启用/禁用 用户
     *
     * @responseField data bool 是否启用/禁用成功
     *
     * @return bool
     */
    public function enable($id)
    {
        return $this->user->toggleBy($id);
    }

    /**
     * 获取登录用户/保存用户信息
     *
     * `online` 接口的 `Get` 请求可以获取用户信息，`Post` 请求可以保存用户信息。
     * 对应的响应数据是 `Get` 请求的，而 `Body` 参数则是用来保存用户信息的参数
     *
     * @bodyParam avatar string 头像
     * @bodyParam email string 邮箱
     * @bodyParam username string 用户名
     * @bodyParam email string 邮箱
     *
     * @responseField id int 用户ID
     * @responseField username string 用户名
     * @responseField avatar string 头像
     * @responseField email string 邮箱
     * @responseField mobile string 手机号
     * @responseField department_id int 部门ID
     * @responseField roles integer[] 角色
     * @responseField jobs integer[] 职位
     * @responseField permissions integer[] 权限
     * @responseField created_at string 创建时间
     *
     * @return Authenticatable
     *
     * @throws AuthenticationException
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
     * 登录日志
     *
     * @responseField id int 日志ID
     * @responseField account string 账户:用户名/邮箱/手机号
     * @responseField login_ip string 登录IP
     * @responseField login_at string 登录时间
     * @responseField browser string 浏览器
     * @responseField platform string 操作系统
     * @responseField status string 登录状态
     * @responseField location string 登录地点
     *
     * @return LengthAwarePaginator
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface|AuthenticationException
     */
    public function loginLog(LogLogin $logLogin)
    {
        $user = $this->getLoginUser();

        return $logLogin->getUserLogBy($user->isSuperAdmin() ? null : $user->email);
    }

    /**
     * 操作日志
     *
     * @queryParam scope string 指定查询范围 self|all，默认为 `self`
     *
     * @responseField id integer 日志ID
     * @responseField creator string 创建人
     * @responseField action string 操作
     * @responseField ip string IP地址
     * @responseField module string 模块
     * @responseField http_method string 请求方法
     * @responseField http_code integer HTTP状态码
     * @responseField start_at string 开始时间
     * @responseField time_taken float 耗时（毫秒）
     * @responseField params string 请求参数
     * @responseField created_at string 创建时间
     */
    public function operateLog(LogOperate $logOperate, Request $request): mixed
    {
        $scope = $request->get('scope', 'self');

        return $logOperate->setBeforeGetList(function ($builder) use ($scope) {
            if ($scope == 'self') {
                return $builder->where('creator_id', $this->getLoginUserId());
            }

            return $builder;
        })->getList();
    }

    /**
     * 导出用户
     *
     * @responseField  content string 响应文件流，无字段
     */
    public function export(): mixed
    {
        return User::query()
            ->select('id', 'username', 'email', 'created_at')
            ->without('roles')
            ->get()
            ->download(['id', '昵称', '邮箱', '创建时间']);
    }

    /**
     * 导入用户
     *
     * @bodyParam file file required 导入 excel 文件
     *
     * @return array|int|UserImport
     */
    public function import(Request $request, UserImport $import)
    {
        return $import->import($request->file('file'));
    }

    /**
     * 回收站恢复
     *
     * @responseField data bool 是否恢复成功
     *
     * @return mixed
     */
    public function restore($id)
    {
        return $this->user->restoreBy($id);
    }
}
