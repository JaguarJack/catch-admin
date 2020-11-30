<?php
declare(strict_types=1);

/**
 * @filename  WechatResponseException.php
 * @createdAt 2020/6/21
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\exceptions;

use catcher\Code;

class WechatResponseException extends CatchException
{
    protected $code = Code::WECHAT_RESPONSE_ERROR;
}