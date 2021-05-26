<?php
namespace catchAdmin\cms\tables\forms;

use catcher\library\form\Form;

class Users extends Form
{
    public function fields(): array
    {
        // TODO: Implement fields() method.
        return [
            self::input('username', '用户名')->required()->clearable(true),

            self::image('头像', 'avatar'),

            self::email('email', '邮箱')->required()->clearable(true),

            self::input('password', '密码')->required()->appendValidates([
                self::validatePassword()
            ])->clearable(true),

            self::input('mobile', '手机号')->appendValidates([
                self::validateMobile()
            ])->clearable(true),


            self::radio('status', '状态', \catchAdmin\cms\model\Users::ENABLE)
            ->options(
                self::options()->add('启用', \catchAdmin\cms\model\Users::ENABLE)
                    ->add('禁用', \catchAdmin\cms\model\Users::DISABLE)->render()
            )
        ];
    }
}