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
            $data = ['code' => $code, 'errors' => $errors, 'message' => $message];
            if (config('app.debug') && $code >= 500) {
                $file = $exception->getFile();
                $file = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
                $line = $exception->getLine();
                $data['file'] = "$file. Line:[$line]";
                $data['trace'] = $this->parseTrace($exception);
            }

            return response()->json($data, $code);
        }
        if ($code == 200) {
            return response($message);
        }
        return parent::render($request, $exception);
    }

    /**
     * @param Exception $exception
     * @return array
     */
    public function parseTrace($exception)
    {
        $t = $exception->getTrace();

        $trace = [];

        foreach ($t as $i => $item) {
            $file = array_get($item, 'file', '[internal function]');
            $file = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
            $line = array_get($item, 'line');

            $class = array_get($item, 'class');
            $func = array_get($item, 'function');
            $type = array_get($item, 'type');
            $line = $line ? '(' . $line . ')' : '';
            $args = array_get($item, 'args', []);

            $ar = '';
            foreach ($args as $arg) {
                $a = $arg;

                if (is_object($a)) $a = get_class($a);

                if (is_array($a)) {
                    $a = '[]';
                } else if (is_string($a)) {
                    $a = "'" . $a . "'";
                } else if (is_bool($a)) {
                    $a = $a ? 'true' : 'false';
                }

                $ar .= $a . ', ';
            }


            $ar = rtrim($ar, ', ');

            $trace[] = '[#' . $i . '] ' . $file . $line . ': ' . $class . $type . $func . '(' . $ar . ')';
        }

        return $trace;
    }
}