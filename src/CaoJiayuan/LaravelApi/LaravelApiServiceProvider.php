<?php
/**
 * Created by PhpStorm.
 * User: 0x01301c74
 * Date: 2017/8/20
 * Time: 19:10
 */

namespace CaoJiayuan\LaravelApi;


use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Mnabialek\LaravelSqlLogger\Providers\ServiceProvider as SqlLoggerServiceProvider;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class LaravelApiServiceProvider extends ServiceProvider
{

    protected $configs = ['laravel-api'];

    public function boot()
    {
        $this->publish();
    }

    public function publish()
    {
        $resources = __DIR__ . '/../../resources';

        $resourcePath = resource_path();

        $configs = __DIR__ . '/../../config';

        $configPath = config_path();

        $this->publishes([
            $resources => $resourcePath,
            $configs => $configPath
        ]);
    }

    public function register()
    {
        $this->mergeConfig();
        $this->app->register(IdeHelperServiceProvider::class);
        $this->app->register(SqlLoggerServiceProvider::class);
        $this->app->register(LaravelServiceProvider::class);

    }

    public function mergeConfig()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];
        /** @var \Illuminate\Filesystem\Filesystem $file */
        $file = $this->app['files'];

        foreach ($this->configs as $key) {
            if (!$config->has($key)) {
                $value = $file->getRequire(__DIR__.'/../../config/'. $key. '.php');
                $config->set($key, $value);
            }
        }
    }
}
