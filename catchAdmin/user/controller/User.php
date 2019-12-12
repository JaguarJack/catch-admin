<?php
namespace catchAdmin\user\controller;

use app\Request;
use catchAdmin\permissions\model\Roles;
use catchAdmin\user\model\Users;
use catchAdmin\user\request\CreateRequest;
use catchAdmin\user\request\UpdateRequest;
use catcher\base\CatchController;
use catcher\CatchForm;
use catcher\CatchResponse;
use catcher\Tree;

class User extends CatchController
{
    protected $user;

    public function __construct(Users $user)
    {
       $this->user = $user;
    }

    /**
     *
     * @time 2019年12月04日
     * @throws \Exception
     * @return string
     */
    public function index()
    {
        return $this->fetch();
    }

    public function list(Request $request)
    {
        return CatchResponse::paginate($this->user->getList($request->param()));
    }

    /**
     *
     * @time 2019年12月06日
     * @throws \Exception
     * @return string
     */
    public function create()
    {
        $form = new CatchForm();

        $form->formId('userForm');
        $form->text('username', '用户名', true)->verify('required')->placeholder('请输入用户名');
        $form->text('email', '邮箱', true)->verify('email')->placeholder('请输入邮箱');
        $form->password('password', '密码', true)->id('pwd')->verify('required|psw')->placeholder('请输入密码');
        $form->password('passwordConfirm', '确认密码', true)->verify('required|equalTo', ['pwd', '两次密码输入不一致'])->placeholder('请再次输入密码');
        $form->dom('<div id="roles"></div>', '角色');
        $form->formBtn('submitUser');

        return $this->fetch([
            'form' => $form->render(),
        ]);
    }

    /**
     *
     * @param CreateRequest $request
     * @time 2019年12月06日
     * @return \think\response\Json
     */
    public function save(CreateRequest $request)
    {
        $this->user->storeBy($request->post());

        if (!empty($request->param('roleids'))) {
            $this->user->attach($request->param('roleids'));
        }

        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return \think\response\Json
     */
    public function read($id)
    {
        return CatchResponse::success($this->user->findBy($id));
    }

    public function edit($id)
    {
        $user = $this->user->findBy($id, ['id','username', 'email']);
        $form = new CatchForm();

        $form->formId('userForm');
        $form->text('username', '用户名', true)->verify('required')->default($user->username)->placeholder('请输入用户名');
        $form->text('email', '邮箱', true)->verify('email')->default($user->email)->placeholder('请输入邮箱');
        $form->password('password', '密码')->id('pwd')->placeholder('请输入密码');
        $form->password('passwordConfirm', '确认密码')->verify('equalTo', ['pwd', '两次密码输入不一致'])->placeholder('请再次输入密码');
        $form->dom('<div id="roles"></div>', '角色');
        $form->formBtn('submitUser');

        return $this->fetch([
            'form' => $form->render(),
            'uid'  => $user->id,
        ]);
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @param UpdateRequest $request
     * @return \think\response\Json
     */
    public function update($id, UpdateRequest $request)
    {
        $this->user->updateBy($id, $request->post());

        $user = $this->user->findBy($id);

        $user->detach();

        if (!empty($request->param('roleids'))) {
            $user->attach($request->param('roleids'));
        }

        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        // 删除角色
        $this->user->findBy($id)->detach();

        $this->user->deleteBy($id);

        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月07日
     * @param $id
     * @return \think\response\Json
     */
    public function switchStatus($id): \think\response\Json
    {
        $user = $this->user->findBy($id);
        return CatchResponse::success($this->user->updateBy($id, [
            'status' => $user->status == Users::ENABLE ? Users::DISABLE : Users::ENABLE,
        ]));
    }

    /**
     *
     * @time 2019年12月07日
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function recover($id): \think\response\Json
    {
       $trashedUser = $this->user->findBy($id, ['*'], true);

       if ($this->user->where('email', $trashedUser->email)->find()) {
           return CatchResponse::fail(sprintf('该恢复用户的邮箱 [%s] 已被占用', $trashedUser->email));
       }

       return CatchResponse::success($this->user->recover($id));
    }

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @param Roles $roles
     * @return \think\response\Json
     */
    public function getRoles(Request $request, Roles $roles): \think\response\Json
    {
        $roles = Tree::done($roles->getList());

        $roleIds = [];
        if ($request->param('uid')) {
            $userHasRoles = $this->user->findBy($request->param('uid'))->getRoles();
            foreach ($userHasRoles as $role) {
                $roleIds[] = $role->pivot->role_id;
            }
        }

        return CatchResponse::success([
            'roles' => $roles,
            'hasRoles' => $roleIds,
        ]);
    }
}