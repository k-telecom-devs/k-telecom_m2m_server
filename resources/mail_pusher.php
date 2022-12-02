<?php

use App\Http\Controllers\MailController;
use App\Models\Sensor;
use App\Models\Station;
use App\Models\User;

$sensors = Sensor::with(['settings'])->get()->all();

foreach ($sensors as $sensor)
{
    try {
        $u = new DateTime($sensor['updated_at']);
        $s = $sensor['settings']['sleep'] * 2;
        $a = $u->modify("+3 minutes")->modify("+$s seconds");
        $name = $sensor['settings']['name'];

        if(new DateTime() > $a)
        {
            $st = Station::find($sensor['station_id']);
            $us = User::find($st['user_id']);

            $mailer = new MailController();
            $content = "Ваш датчик с именем $name не присылает данные, проверьте подключение датчика к сети.";

            try {
                $mailer->sendMail($us['email'], $content, 'Проверьте датчик!');
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

}
