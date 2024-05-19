<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->resource('/', HomeController::class);
    $router->resource('account', AccountController::class);
    $router->resource('account-record', AccountRecordController::class);
    $router->resource('account-record-tags', AccountRecordTagsController::class);
});
