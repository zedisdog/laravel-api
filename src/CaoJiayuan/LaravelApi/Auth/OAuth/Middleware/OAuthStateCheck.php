<?php
/**
 * Created by PhpStorm.
 * User: caojiayuan
 * Date: 2017/10/30
 * Time: 下午9:41
 */

namespace CaoJiayuan\LaravelApi\Auth\OAuth\Middleware;


use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OAuthStateCheck
{
    public function handle($request, Closure $next)
    {
        $state = $request->get('state');

        $sessionToken = $request->session()->token();

        if (! is_string($sessionToken) || ! is_string($state) || !hash_equals($sessionToken, $state)) {
            throw new HttpException(403, 'Access denied!');
        }

        return $next($request);
    }
}