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
        
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = env('MAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('MAIL_USERNAME');
        $mail->Password = env('MAIL_PASSWORD');
        $mail->SMTPSecure = env('MAIL_ENCRYPTION');
        $mail->Port = env('MAIL_PORT');
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('m2m_server@k-telecom.org', 'K-Telecom');
        $mail->addAddress($request->email);
        

        $mail->isHTML(true);
        $mail->Subject = 'Test';
        $code = rand(100000,1000000);
        $mail->Body = $code;
        try{
            $mail->send();
            return response()->json(['message' => 'Mail send', 'code'=> hash('md5', $code)]);
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