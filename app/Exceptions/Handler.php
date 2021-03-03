<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if (env('APP_DEBUG')) {
            return parent::render($request, $exception);
        } else {
            if ($exception instanceof NotFoundHttpException) {
                $globalStatus = 404;
                $errors[] = array(
                    'status' => $globalStatus,
                    'title' => 'Endpoint Tidak Ditemukan'
                );
                if (!app()->environment('production')) {
                    $errors[0]['source'] = $exception->getFile();
                    $errors[0]['detail'] = $exception->getTraceAsString();
                }
                return response()->json([
                    "errors" => $errors
                ], $globalStatus);
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                $globalStatus = Response::HTTP_METHOD_NOT_ALLOWED;
                $errors[] = array(
                    'status' => $globalStatus,
                    'title' => 'Method Tidak Diperbolehkan'
                );
                if (!app()->environment('production')) {
                    $errors[0]['source'] = $exception->getFile();
                    $errors[0]['detail'] = $exception->getTraceAsString();
                }
                return response()->json([
                    "errors" => $errors
                ], $globalStatus);
            } else if ($exception instanceof AuthorizationException) {
                $globalStatus = 403;
                $errors[] = array(
                    'status' => $globalStatus,
                    'title' => $exception->getMessage(),
                );
                if (!app()->environment('production')) {
                    $errors[0]['source'] = $exception->getFile();
                    $errors[0]['detail'] = $exception->getTraceAsString();
                }
                return response()->json([
                    "errors" => $errors
                ], $globalStatus);
            } else if ($exception instanceof AuthenticationException) {
                $globalStatus = 401;
                $errors[] = array(
                    'status' => $globalStatus,
                    'title' => $exception->getMessage(),
                );
                if (!app()->environment('production')) {
                    $errors[0]['source'] = $exception->getFile();
                    $errors[0]['detail'] = $exception->getTraceAsString();
                }
                return response()->json([
                    "errors" => $errors
                ], $globalStatus);
            } else if ($exception instanceof ValidationException) {
                $globalStatus = 422;
                $errors[] = array(
                    'status' => $globalStatus,
                    'title' => 'Cek Kembali, data yang kamu berikan kurang lengkap atau kurang benar',
                    'detail' => $exception->validator->errors()
                );
                if (!app()->environment('production')) {
                    $errors[0]['source'] = $exception->getFile();
                }
                return response()->json([
                    "errors" => $errors
                ], $globalStatus);
            } else {
                $globalStatus = 500;
                $errors[] = array(
                    'status' => $globalStatus,
                    'title' => 'Mohon Maaf, Terjadi Kesalahan Sistem'
                );
                if (!app()->environment('production')) {
                    $errors[0]['source'] = array(
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine()
                    );
                    $errors[0]['detail'] = $exception->getTraceAsString();
                } else {
                    Log::critical(json_encode(
                        array(
                            'source' => array(
                                'file' => $exception->getFile(),
                                'line' => $exception->getLine()
                            ),
                            'detail' => $exception->getTraceAsString()
                        )
                    ));
                }
                return response()->json([
                    "errors" => $errors
                ], $globalStatus);
            }
        }
    }
}
