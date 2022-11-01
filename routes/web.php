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

    $router->post('/mail', 'MailController@index');
    
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/refresh', 'AuthController@refreshToken');        

        $router->post('/logout', 'AuthController@logout');
        $router->post('/profile-change', 'AuthController@profile_change');
        $router->get('/profile-change', 'AuthController@get_profile');

        $router->post('/group', 'GroupController@create');
        $router->post('/subgroup', 'SubgroupController@create');

        $router->get('/data', 'DataController@index');

        $router->get('/station', 'StationController@index');
        $router->post('/station', 'StationController@create');

        $router->get('/station-settings', 'StationSettingsController@index');
        $router->post('/station-settings', 'StationSettingsController@edit');

        $router->post('/version', 'VersionController@create');

        $router->get('/sensor', 'SensorController@index');
        $router->post('/sensor', 'SensorController@create');
        $router->post('/del', 'SensorController@del');

        $router->post('/sensor-settings', 'SensorSettingsController@edit');

        $router->post('/device-type', 'DeviceTypeController@create');

        $router->post('/city', 'CityController@store');
        $router->get('/city', 'CityController@index');

    });
});
