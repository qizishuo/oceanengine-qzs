<?php

namespace OceanengineQzs;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * 注册服务
     */
    public function register()
    {
        // 绑定 SDK 到 Laravel 容器
        $this->app->singleton(ApiClient::class, function ($app) {
            return new ApiClient();
        });

        // 合并默认配置
        $this->mergeConfigFrom(__DIR__.'/../config/oceanengine.php', 'oceanengine');
    }

    /**
     * 启动服务
     */
    public function boot()
    {
        // 自动加载路由
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // 发布配置文件
        $this->publishes([
            __DIR__.'/../config/oceanengine.php' => config_path('oceanengine.php'),
        ], 'config');
    }
}

