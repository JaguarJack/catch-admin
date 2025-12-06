<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 ~ now https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Modules\User\Models\User;

class PasswordCommand extends Command
{
    protected $signature = 'catch:pwd';

    protected $description = '更新后台用户密码';

    public function handle(): void
    {
        if (config('app.debug')) {
            $email = $this->ask('👉 请输入修改用户的邮箱');

            $user = User::query()->where('email', $email)->first();

            if ($user) {
                $password = $this->ask('👉 请输入修改用户的密码');
                $user->password = $password;
                if ($user->save()) {
                    $this->info('修改密码成功');
                } else {
                    $this->info('修改密码失败');
                }
            } else {
                $this->error('未找到指定邮箱的用户');
            }
        }
    }
}
