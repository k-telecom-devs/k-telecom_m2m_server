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

$router->get('/confirm', 'AuthController@confirm');
$router->get('/new-password', 'AuthController@newPassword');

$router->get('/get-app', function () use ($router) {
    $type = 'application/vnd.android.package-archive';
    $headers = ['Content-Type' => $type];
    $path = storage_path("app/k-telecom-m2m.apk");
    return response()->download($path, 'k-telecom-m2m.apk', $headers);
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

    $router->post('/data', 'DataController@store');
    $router->get('/sensor-settings', 'SensorSettingsController@index');

    $router->post('/mail', 'MailController@sendCode');
    $router->post('/password-reset', 'AuthController@generateResetHash');

    $router->post('/lost_data', 'DataController@lost_data');

    $router->get('/api-version', function () use ($router) {
        return env('APP_VERSION');
    });

    $router->post('/give-me-name', 'SensorController@generateID');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->post('/refresh', 'AuthController@refreshToken');
        $router->post('/deletemyself', 'AuthController@delete');


        $router->group(['middleware' => 'verified'], function () use ($router) {

	    

            $router->post('/profile-change', 'AuthController@profile_change');
            $router->get('/profile-change', 'AuthController@get_profile');

            $router->post('/group', 'GroupController@create');
            $router->post('/subgroup', 'SubgroupController@create');

            $router->get('/data', 'DataController@index');

            $router->get('/station', 'StationController@index');
            $router->post('/station', 'StationController@create');
            $router->delete('/station', 'StationController@del');

            $router->get('/station-settings', 'StationSettingsController@index');
            $router->post('/station-settings', 'StationSettingsController@edit');

            $router->post('/version', 'VersionController@create');
            $router->get('/version', 'VersionController@index');

            $router->get('/sensor', 'SensorController@index');
            $router->post('/sensor', 'SensorController@create');
            $router->delete('/sensor', 'SensorController@del');

            $router->post('/sensor-settings', 'SensorSettingsController@edit');

            $router->post('/device-type', 'DeviceTypeController@create');
            $router->get('/device-type', 'DeviceTypeController@index');

            $router->post('/city', 'CityController@store');
            $router->get('/city', 'CityController@index');

            $router->get('/daily-stat', 'StatController@dailyStat');
            $router->get('/monthly-stat', 'StatController@MonthlyStat');

            $router->post('/group-metrics', 'MetricsController@store');
            $router->get('/group-metrics', 'MetricsController@index');
        });
    });
});
