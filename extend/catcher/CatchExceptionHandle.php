<?php
declare(strict_types=1);

namespace catcher;

use app\ExceptionHandle;
use catcher\exceptions\CatchException;
use catcher\exceptions\FailedException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class CatchExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     * @throws \Exception
     */
    public function render($request, Throwable $e): Response
    {
        // 其他错误交给系统处理
        if ($e instanceof \Exception && !$e instanceof CatchException) {
            $e = new FailedException($e->getMessage(), 10005, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * 重写异常渲染页面
     *
     * @time 2020年05月22日
     * @param Throwable $exception
     * @return string
     */
    protected function renderExceptionContent(Throwable $exception): string
    {
        ob_start();
        $data = $this->convertExceptionToArray($exception->getPrevious() ? $exception->getPrevious() : $exception);
        extract($data);
        include $this->app->config->get('app.exception_tmpl') ?: __DIR__ . '/../../tpl/think_exception.tpl';

        return ob_get_clean();
    }
}
