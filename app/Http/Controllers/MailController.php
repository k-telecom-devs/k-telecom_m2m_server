<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\SMTP;

class MailController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        $mail->SMTPDebug = 4;
        $mail->isSMTP();
        $mail->Host = 'smtp.yandex.ru';
        $mail->SMTPAuth = true;
        $mail->Username = '383d802a4c84af5ac3719276218bb9@gmail.com';
        $mail->Password = '7fc7f9e73856bd42a257ce7aac54fc3687f7ad60';
        $mail->SMTPSecure = 'SSL';
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('383d802a4c84af5ac3719276218bb9@gmail.com', 'K-Telecom');
        $mail->addAddress($user['email']);//$request->email);
        

        $mail->isHTML(true);
        $mail->Subject = 'Test';
        $mail->Body = 'send smth';

        try{
            return $mail->send();
        }

        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'city_name' => 'required',
        ]);

        try {

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}



/*
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Mail\Mail;

class MailController extends Controller
{
    public function index()
    {
        Mail::send(['text'=>'mail'], ['name'=>'Письмо подтверждения'], function($message){
            $message->to('mikmez01@gmail.com','to me')->subject('test email');
            $message->from('mikmezrin@gmail.com','from me');
        });
    } 
}*/