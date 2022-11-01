<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\SMTP;

class MailController extends Controller
{
    public function code(Request $request): JsonResponse
    {
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
        $mail->Subject = 'K-telecom message';
        
        
        $code = rand(100000,999999);
        $mail->Body = $code;
        try{
            if ($mail->send()){
            return response()->json(['message' => 'Mail send', 'code'=> hash('md5', $code)]);
            }
            else{
                return response()->json(['message' => 'Something gone wrong']);
            }
        }

        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

    }

    //Отдельная функция для отправки письма с любым view
    public function sendMail(string $user_email, string $content, string $subject): JsonResponse
    {
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
        $mail->addAddress($user_email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $content;
        try{
        if ($mail->send()){
            return response()->json(['message' => 'Mail send']);
        } 
        else{
            return response()->json(['message' => 'Something gone wrong']);
        }
        }
        
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }

        
    }
}