<?php
/**
 * Created by PhpStorm.
 * User: caojiayuan
 * Date: 17-9-8
 * Time: 下午2:08
 */

namespace CaoJiayuan\LaravelApi\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SimpleJWTAuthController extends Controller
{

    /**
     * The user has been authenticated.
     *
     * @param  Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->token = $this->getJWTToken($request, $user);

        return $user;
    }

    protected function getJWTToken(Request $request, $user)
    {
        return JWTAuth::fromUser($user, $this->getCustomClaims($request, $user));
    }

    protected function getCustomClaims(Request $request, $user)
    {
        return [];
    }
}