<?php
/**
 * Created by PhpStorm.
 * User: caojiayuan
 * Date: 17-9-8
 * Time: 下午2:38
 */

namespace CaoJiayuan\LaravelApi\Auth\Middleware;


use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Payload;

class Auth extends Authenticate
{



    public function handle($request, Closure $next, ...$guards)
    {
        $token = $this->getToken($request);
        $payload = JWTAuth::getPayload($token);

        $this->authenticateWithPayload($payload);

        return $next($request);
    }



    /**
     * @param Payload $payload
     * @return User
     */
    protected function authenticateWithPayload($payload)
    {
        $id = $payload->get('sub');

    }

    /**
     * @param Request $request
     * @return bool|string
     */
    protected function getToken($request)
    {
        if (!$token = JWTAuth::getToken()) {
            $token = $request->bearerToken();
        }

        return $token;
    }

}