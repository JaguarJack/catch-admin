<?php

namespace Modules\User\Models;

use Catch\CatchAdmin;
use Catch\Traits\DB\BaseOperate;
use Catch\Traits\DB\ScopeTrait;
use Catch\Traits\DB\Trans;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class LogOperate extends Model
{
    use BaseOperate, Trans, ScopeTrait;

    protected $table = 'log_operate';

    protected $fillable = [
        'id',
        'module',
        'action',
        'params',
        'ip',
        'http_method',
        'http_code',
        'start_at',
        'time_taken',
        'creator_id',
        'created_at',
    ];

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function log(Request $request, Response $response): void
    {
        $user = Auth::guard(getGuardName())->user();

        $userModel = getAuthUserModel();

        if (! $user instanceof $userModel) {
            return;
        }

        [$module, $controller, $action] = CatchAdmin::parseFromRouteAction();

        $requestStartAt = app(Kernel::class)->requestStartedAt()->getPreciseTimestamp(3);
        $params = $request->all();
        // 如果参数过长则不记录
        if (!empty($params)) {
            if (strlen(\json_encode($params, JSON_UNESCAPED_UNICODE)) > 5000) {
                $params = [];
            }
        }

        $timeTaken = intval(microtime(true) * 1000 - $requestStartAt);
        $this->storeBy([
            'module' => $module,
            'action' => $controller . '@' . $action,
            'creator_id' => $user->id,
            'http_method' => $request->method(),
            'http_code' => $response->getStatusCode(),
            'start_at' => intval($requestStartAt/1000),
            'time_taken' => $timeTaken,
            'ip' => $request->ip(),
            'params' => \json_encode($params, JSON_UNESCAPED_UNICODE),
            'created_at' => time()
        ]);
    }

    /**
     *
     * @return Attribute
     */
    protected function timeTaken(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value > 1000 ? intval($value/1000) . 's' : $value . 'ms',
        );
    }
}
