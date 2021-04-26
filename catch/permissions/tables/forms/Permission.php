<?php
namespace catchAdmin\permissions\tables\forms;

use catchAdmin\permissions\model\Permissions;
use catcher\CatchAdmin;
use catcher\library\form\Form;

class Permission extends Form
{
    public function fields(): array
    {
        $this->getModules();
        // TODO: Implement fields() method.
        return [
            self::cascader('parent_id', '父级菜单', [])->options(
                Permissions::where('type', Permissions::MENU_TYPE)->field(['id', 'permission_name', 'parent_id'])
                    ->select()->toTree()
            )->col(12)->props(self::props('permission_name', 'id', [
                'checkStrictly' => true
            ]))->filterable(true)->clearable(true)->style(['width' => '100%']),

            self::radio('type', '菜单类型')
                ->button()
                ->value(1)
                ->options(
                    self::options()->add('菜单', 1)->add('按钮', 2)->render()
                )->appendControl(
                   1,
                       [
                            self::input('permission_name', '菜单名称')->required()->col(12),
                            self::input('permission_mark', '权限标识')->required()->col(12),

                            self::select('module', '模块')
                                ->required()
                                ->style(['width' => '100%'])
                                ->allowCreate(true)
                                ->filterable(true)
                                ->clearable(true)
                                ->col(12)
                                ->options($this->getModules()),

                            self::input('icon', '菜单图标')
                                ->col(12)
                                ->style(['width' => '100%'])
                                ->clearable(true),

                            self::input('route', '菜单Path')->col(12),

                            self::cascader('component', '组件')
                                ->col(12)
                                ->options([])
                                ->style(['width' => '100%'])
                                ->showAllLevels(false),

                            self::input('redirect', 'Redirect')->col(12),
                            self::number('sort', '排序')->value(1)->col(12),

                            self::radio('keepalive', 'Keepalive')
                                ->value(1)
                                ->col(12)
                                ->options(
                                    self::options()->add('启用', 1)
                                                    ->add('禁用', 2)
                                                    ->render()
                                ),

                            self::radio('hidden', 'Hidden')->value(1)->options(
                                self::options()->add('显示', 1)->add('隐藏', 2)->render()
                            )->col(12),

                           self::radio('restful', 'Restful 路由')->value(0)->options(
                               self::options()->add('生成', 1)->add('不生成', 0)->render()
                           )->col(12)
                       ]
                )
                 ->appendControl( 2,
                        [
                             self::select('permission_name', '菜单名称')
                                 ->allowCreate(true)
                                 ->filterable(true)
                                 ->options(
                                     self::options()->add('列表', '列表')
                                         ->add('创建', '创建')
                                     ->add('更新', '更新')->add('读取', '读取')
                                     ->add('删除', '删除')->add('禁用/启用', '禁用/启用')
                                     ->add('导出', '导出')->add('导入', '导入')->render()
                                 )
                                 ->required()->style(['width' => '100%'])->col(12),
                             self::select('permission_mark', '权限标识')
                                 ->allowCreate(true)
                                 ->filterable(true)
                                 ->options(
                                     self::options()->add('index', 'index')
                                         ->add('save', 'save')
                                         ->add('update', 'update')->add('read', 'read')
                                         ->add('delete', 'delete')->add('disable', 'disable')
                                         ->add('export', 'export')->add('import', 'import')->render()
                                 )
                                 ->required()->col(12),
                             self::number('sort', '排序')->value(1)->col(12),
                        ]
                 )->col(12)
        ];
    }


    /**
     * 获取模块
     *
     * @time 2021年03月31日
     * @return array
     */
    protected function getModules(): array
    {
        $modules = [];

        foreach(CatchAdmin::getModulesDirectory() as $d) {
            $module = CatchAdmin::getModuleInfo($d);

            if (!isset($module['alias'])) {
                continue;
            }

            if (in_array($module['alias'], ['login'])) {
                continue;
            }

            if ($module['enable']) {
                $modules[] = [
                    'value' => $module['alias'],
                    'label' => $module['name']
                ];
            }
        }

        return $modules;
    }

    /**
     * icons
     *
     * @time 2021年03月31日
     * @return array
     */
    protected function getIcons(): array
    {
        $icons = ['platform-eleme', 'eleme', 'delete-solid', 'delete', 's-tools', 'setting', 'user-solid', 'user', 'phone', 'phone-outline', 'more', 'more-outline', 'star-on', 'star-off', 's-goods', 'goods', 'warning', 'warning-outline', 'question', 'info', 'remove', 'circle-plus', 'success', 'error', 'zoom-in', 'zoom-out', 'remove-outline', 'circle-plus-outline', 'circle-check', 'circle-close', 's-help', 'help', 'minus', 'plus', 'check', 'close', 'picture', 'picture-outline', 'picture-outline-round', 'upload', 'upload2', 'download', 'camera-solid', 'camera', 'video-camera-solid', 'video-camera', 'message-solid', 'bell', 's-cooperation', 's-order', 's-platform', 's-fold', 's-unfold', 's-operation', 's-promotion', 's-home', 's-release', 's-ticket', 's-management', 's-open', 's-shop', 's-marketing', 's-flag', 's-comment', 's-finance', 's-claim', 's-custom', 's-opportunity', 's-data', 's-check', 's-grid', 'menu', 'share', 'd-caret', 'caret-left', 'caret-right', 'caret-bottom', 'caret-top', 'bottom-left', 'bottom-right', 'back', 'right', 'bottom', 'top', 'top-left', 'top-right', 'arrow-left', 'arrow-right', 'arrow-down', 'arrow-up', 'd-arrow-left', 'd-arrow-right', 'video-pause', 'video-play', 'refresh', 'refresh-right', 'refresh-left', 'finished', 'sort', 'sort-up', 'sort-down', 'rank', 'loading', 'view', 'c-scale-to-original', 'date', 'edit', 'edit-outline', 'folder', 'folder-opened', 'folder-add', 'folder-remove', 'folder-delete', 'folder-checked', 'tickets', 'document-remove', 'document-delete', 'document-copy', 'document-checked', 'document', 'document-add', 'printer', 'paperclip', 'takeaway-box', 'search', 'monitor', 'attract', 'mobile', 'scissors', 'umbrella', 'headset', 'brush', 'mouse', 'coordinate', 'magic-stick', 'reading', 'data-line', 'data-board', 'pie-chart', 'data-analysis', 'collection-tag', 'film', 'suitcase', 'suitcase-1', 'receiving', 'collection', 'files', 'notebook-1', 'notebook-2', 'toilet-paper', 'office-building', 'school', 'table-lamp', 'house', 'no-smoking', 'smoking', 'shopping-cart-full', 'shopping-cart-1', 'shopping-cart-2', 'shopping-bag-1', 'shopping-bag-2', 'sold-out', 'sell', 'present', 'box', 'bank-card', 'money', 'coin', 'wallet', 'discount', 'price-tag', 'news', 'guide', 'male', 'female', 'thumb', 'cpu', 'link', 'connection', 'open', 'turn-off', 'set-up', 'chat-round', 'chat-line-round', 'chat-square', 'chat-dot-round', 'chat-dot-square', 'chat-line-square', 'message', 'postcard', 'position', 'turn-off-microphone', 'microphone', 'close-notification', 'bangzhu', 'time', 'odometer', 'crop', 'aim', 'switch-button', 'full-screen', 'copy-document', 'mic', 'stopwatch', 'medal-1', 'medal', 'trophy', 'trophy-1', 'first-aid-kit', 'discover', 'place', 'location', 'location-outline', 'location-information', 'add-location', 'delete-location', 'map-location', 'alarm-clock', 'timer', 'watch-1', 'watch', 'lock', 'unlock', 'key', 'service', 'mobile-phone', 'bicycle', 'truck', 'ship', 'basketball', 'football', 'soccer', 'baseball', 'wind-power', 'light-rain', 'lightning', 'heavy-rain', 'sunrise', 'sunrise-1', 'sunset', 'sunny', 'cloudy', 'partly-cloudy', 'cloudy-and-sunny', 'moon', 'moon-night', 'dish', 'dish-1', 'food', 'chicken', 'fork-spoon', 'knife-fork', 'burger', 'tableware', 'sugar', 'dessert', 'ice-cream', 'hot-water', 'water-cup', 'coffee-cup', 'cold-drink', 'goblet', 'goblet-full', 'goblet-square', 'goblet-square-full', 'refrigerator', 'grape', 'watermelon', 'cherry', 'apple', 'pear', 'orange', 'coffee', 'ice-tea', 'ice-drink', 'milk-tea', 'potato-strips', 'lollipop', 'ice-cream-square', 'ice-cream-round'];

        $options = self::options();

        foreach ($icons as $icon) {
            $icon = 'el-icon-' . $icon;

            $options->add(htmlspecialchars(sprintf('<i class=\"%s\"></i> %s', $icon, $icon)), $icon);
        }

        return $options->render();
    }
}