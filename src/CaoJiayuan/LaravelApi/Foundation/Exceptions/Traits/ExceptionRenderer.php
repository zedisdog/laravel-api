<?php
/**
 * Created by PhpStorm.
 * User: caojiayuan
 * Date: 17-10-31
 * Time: 上午9:40
 */

namespace CaoJiayuan\LaravelApi\Foundation\Exceptions\Traits;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
trait ExceptionRenderer
{

    /**
     * @param Request $request
     * @param Exception $exception
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function renderException($request, Exception $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }
        $code = 500;
        if ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }
        $message = $exception->getMessage();
        if ($request->expectsJson()) {
            $errors = [];
            if ($exception instanceof ValidationException) {
                $code = 422;
                $errors = $exception->validator->getMessageBag();
                $message = $errors->first();
            }
            return response()->json(['code' => $code, 'errors' => $errors, 'message' => $message], $code);
        }
        if ($code == 200) {
            return response($message);
        }
        return parent::render($request, $exception);
    }
}