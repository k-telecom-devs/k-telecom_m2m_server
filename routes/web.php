<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

    $router->post('/data', 'DataController@store');
    $router->get('/sensor-settings', 'SensorSettingsController@index');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');

        $router->get('/data', 'DataController@index');

        $router->get('/sensor', 'SensorController@index');
        $router->post('/sensor', 'SensorController@create');

        $router->get('/station', 'StationController@index');
        $router->post('/station', 'StationController@create');

        $router->get('/station-settings', 'StationSettingsController@index');
        $router->post('/station-settings', 'StationSettingsController@edit');

        $router->post('/version', 'VersionController@create');
        
        $router->post('/sensor-settings', 'SensorSettingsController@edit');
    });
});
