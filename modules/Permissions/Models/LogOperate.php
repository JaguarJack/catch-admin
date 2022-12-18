<?php

namespace Modules\Permissions\Models;

use Catch\CatchAdmin;
use Catch\Traits\DB\BaseOperate;
use Catch\Traits\DB\ScopeTrait;
use Catch\Traits\DB\Trans;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Permissions\Exceptions\PermissionForbidden;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

class LogOperate extends Model
{
    use BaseOperate, Trans, ScopeTrait;

    protected $table = 'log_operate';

    protected $fillable = [
        'id',
        'module',
        'operate',
        'route',
        'params',
        'ip',
        'http_method',
        'http_method',
        'start_at',
        'time_taken',
        'creator_id',
        'created_at',
    ];


    /**
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function log(Request $request, Response $response): void
    {
        if (! $response->isOk() && $response->exception instanceof PermissionForbidden) {
            return;
        }

        $user = Auth::guard(getGuardName())->user();

        $userModel = getAuthUserModel();

        if (! $user instanceof $userModel) {
            return;
        }



        $user->getAttribute('permissions')->each(function ($permission) use ($user, $request, $response) {
            if ($permission->isAction()) {
                [$controller, $action] = explode('@', $permission->permission_mark);

                 if (! CatchAdmin::getModuleControllerNamespace($permission->module).$controller.'Controller@'.$action == Route::currentRouteAction()) {
                     return;
                 }

                $requestStartAt = app(Kernel::class)->requestStartedAt()->timestamp;

                $params = $request->all();
                // 如果参数过长则不记录
                if (!empty($params)) {
                    if (strlen($encodeParams = \json_encode($params, JSON_UNESCAPED_UNICODE)) > 5000) {
                        $params = [];
                    }
                }

                $this->storeBy([
                    'module' => $permission->module,

                    'operate' => $permission->permission_name,

                    'route' => $permission->permission_mark,

                    'creator_id' => $user->id,

                    'http_method' => $request->method(),

                    'http_code' => $response->getStatusCode(),

                    'start_at' => $requestStartAt,

                    'time_taken' => time() - $requestStartAt,

                    'ip' => $request->ip(),

                    'params' => !empty($params) ? $encodeParams : '',

                    'created_at' => time()
                ]);
            }
        });





    }

    /**
     * @return Attribute
     */
    protected function timeTaken(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value . 's',
        );
    }
}
