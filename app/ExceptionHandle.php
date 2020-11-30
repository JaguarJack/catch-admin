<?php

namespace app;

use Throwable;
use think\Response;
use think\Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\exception\HttpResponseException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
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
        if ($e instanceof Exception) {
            // 格式化输出
            if (env('app_debug')) {
                $data = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                    'traceAs' => $e->getTraceAsString(),
                ];
            } else {
                $data = [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ];
            }
            return json($data, 403);
        }
        $this->isJson = true;
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof HttpException) {
            return $this->renderHttpException($e);
        } else {
            return $this->convertExceptionToResponse($e);
        }
        // 格式化输出
        if (env('app_debug')) {
            $data = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
                'traceAs' => $e->getTraceAsString(),
            ];
        } else {
            $data = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        return json($data, 403);
        // 其他错误交给系统处理
        // return parent::render($request, $e);
    }
}
