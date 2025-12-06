<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\Facade\Module;
use Illuminate\Support\Facades\DB;
use Modules\Develop\Support\Generate\Exception\MenuCreateFailException;
use Modules\Permissions\Enums\MenuType;
use Modules\Permissions\Models\Permissions;

/**
 * 自动创建菜单
 */
class Menu
{
    protected bool $isDialogForm = true;

    public function __construct(
        public readonly array $gen
    ) {}

    /**
     * 菜单生成
     */
    public function generate(): mixed
    {
        // 如果设置了名称
        if ($this->gen['menu']) {
            return DB::transaction(function () {
                $topMenu = Permissions::where('module', $this->gen['module'])->first();
                // 如果系统模块没有顶级菜单，则需要创建顶级菜单
                if (! $topMenu) {
                    $module = Module::show($this->gen['module']);
                    $topMenuId = app(Permissions::class)->storeBy([
                        'component' => '/layout/index.vue',
                        'hidden' => 1,
                        'keepalive' => 1,
                        'module' => $this->gen['module'],
                        'parent_id' => 0,
                        'permission_name' => $module['title'],
                        'route' => '/'.$this->gen['module'],
                        'sort' => 1,
                        'type' => MenuType::Top->value,
                    ]);
                } else {
                    $topMenuId = $topMenu->id;
                }
                if ($topMenuId) {
                    $controller = lcfirst($this->gen['controller']);
                    if (Permissions::where('parent_id', $topMenuId)
                        ->where('permission_name', $this->gen['menu'])
                        ->first()
                    ) {
                        throw new MenuCreateFailException('文件创建成功，但是由于存在下有相同菜单，创建菜单失败，请手动添加');
                    }
                    $id = app(Permissions::class)->storeBy([
                        'component' => '/'.$this->gen['module'].'/'.$controller.'/index.vue',
                        'hidden' => 1,
                        'keepalive' => 1,
                        'module' => $this->gen['module'],
                        'parent_id' => $topMenuId,
                        'permission_name' => $this->gen['menu'],
                        'permission_mark' => $controller,
                        'route' => $controller,
                        'sort' => 1,
                        'type' => MenuType::Menu->value,
                    ]);

                    // 如果没有使用弹窗表单
                    if (! $this->isDialogForm) {
                        $this->addCreateFormMenu($controller, $topMenuId);
                    }

                    // 生成 actions
                    app(Permissions::class)->storeBy([
                        'actions' => true,
                        'parent_id' => $id,
                        'type' => MenuType::Action->value(),
                    ]);
                }
            });
        }

        return false;
    }

    /**
     * 添加创建页面表单
     *
     * @param  $controller
     * @param  $topMenuId
     * @return void
     */
    protected function addCreateFormMenu($controller, $topMenuId): void
    {
        app(Permissions::class)->storeBy([
            'component' => '/'.$this->gen['module'].'/'.$controller.'/create.vue',
            'hidden' => 2,
            'keepalive' => 2,
            'module' => $this->gen['module'],
            'parent_id' => $topMenuId,
            'permission_name' => '创建'.$this->gen['menu'],
            'permission_mark' => $controller.'_'.'create',
            'route' => $controller.'/create:id?',
            'sort' => 1,
            'type' => MenuType::Menu->value,
        ]);
    }

    /**
     * 是否使用弹窗表单
     *
     * @param  $isDialogForm
     * @return $this
     */
    public function useDialogForm($isDialogForm): static
    {
        $this->isDialogForm = $isDialogForm;

        return $this;
    }
}
