<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\MailController;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $phone_number = $request->phone_number;

        if (empty($name) or empty($email) or empty($password) or empty($phone_number) or $phone_number <= 9000000000) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'error', 'message' => 'You must enter a valid email']);
        }

        if (strlen($password) < 6) {
            return response()->json(['status' => 'error', 'message' => 'Password should be min 6 character']);
        }

        if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User already exists with this email']);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = app('hash')->make($request->password);
            $user->phone_number = $phone_number;
            $user->user_hash = hash('sha512',$name.$email.$phone_number);


            if ($user->save()) {
                $newMail = new MailController();
                if($newMail->sendMail($request->email,'Follow this link '.env('SERVER_URL').'/confirm?fbcc689837324a00d4aa9365a7458715='.$user->user_hash,'K-telecom virfi mail')==true)
                {
                    return $this->login($request);
                }
                else{
                    return'Somthing gone wrong';
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

        return response()->json(['error' => 'Something gone wrong'], 401);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function login(Request $request): JsonResponse
    {
        $email = $request->email;
        $password = $request->password;

        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        $credentials = request(['email', 'password']);

        auth()->factory()->setTTL(1); 

        if (!$token = auth()->attempt($credentials)) { 
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function profile_change(Request $request): JsonResponse
    {   try{
        $u = auth()->user();

        $user = User::find($u['id']);

        //Проверка на bool. MySQL - глупый и мог принять значение >1 || <0
        if ($request->notifications != 1 && $request->notifications != 0){
            return response()->json(['message' => 'notifications can be only 1(true) or 0(false)']);
        }
        if ($request->auto_update != 1 && $request->auto_update != 0){
            return response()->json(['message' => 'auto_update can be only 1(true) or 0(false)']);
        }
        if ($request->auto_pay != 1 && $request->auto_pay != 0){
            return response()->json(['message' => 'auto_pay can be only 1(true) or 0(false)']);
        }


        if (!empty($request->phone_number)){
            $user->phone_number = $request->phone_number;
        }
        if (!empty($request->notifications)){
            $user->notifications = $request->notifications;
        }
        if (!empty($request->auto_update)){
            $user->auto_update = $request->auto_update;
        }
        if (!empty($request->auto_pay)){
            $user->auto_pay = $request->auto_pay;
        }
        if (!empty($request->email)){
            $user->email = $request->email;
        }
        if (!empty($request->password)){
            $user->password = app('hash')->make($request->password);
        }
        if (!empty($request->name)){
            $user->name = $request->name;
        }
        $user->user_hash = hash('sha512',$user['name'].$user['email'].$user['phone_number']);


        if ($user->save()){
            
            return response()->json(['message' => 'Done!']);
        } else {
            return response()->json(['message' => 'Something gone wrong']);
        }
    }
    catch (\Exception $e) {

        return response()->json(['message' => $e->getMessage()]);
    }
}

    public function get_profile(): JsonResponse
    {
        return response()->json(['message' => auth()->user()]);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function refreshToken(): JsonResponse
    {
        auth()->factory()->setTTL(43200);
        return response()->json(['token' => auth()->fromUser(auth()->user())]);
    }


    public function generateResetHash(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if (!$user){
            return response()->json(['message' => "Can't find user with this email"]);
        }
        $newCode = new MailController;
        $newCode::sendMail($request->email, 'Follow this link if you want to reset password.<br>'.env('SERVER_URL').'/new-password?fbcc689837324a00d4aa9365a7458715='.$user['user_hash'], 'Password change');
        if($user->save()){
            return response()->json(['message' => 'Mail send']);                      
        }
        else{
            return response()->json(['message' => 'Something gone wrong']);
        }
    }

    public function newPassword(Request $request)
    {
        $user = User::where('user_hash', $request->fbcc689837324a00d4aa9365a7458715)->first();
        $defaultPassword = rand(1000000000,9999999999);
        $mail = new MailController;
        $mail::sendMail($user['email'], 'Your new password is.<br>'.$defaultPassword.'<br> We advise you to change it as soon as possible', 'Password change');
        $user->password = app('hash')->make($defaultPassword);
        $user->password_reset_hash = null;
        if($user->save()){
        return response()->json(['message' => $defaultPassword]);
    }
        else{
            return response()->json(['message' => "Something gone wrong"]);
        }
    }

    public function confirm(Request $request): JsonResponse
    {
        try{
            $user = User::where('user_hash', $request->fbcc689837324a00d4aa9365a7458715)->first();
            if($user){
                $user->email_verified = true;
                if ($user->save()){
                    return response()->json(['message' => 'Successfully confirm']);
                }
                else{
                    return response()->json(['message' => 'Somthing gone wrong']);
                }
            }
            else{
                return response()->json(['message' => "Can't find user"]);
            }
    }
    catch (\Exception $e) {

        return response()->json(['message' => $e->getMessage()]);
    }
    }
}


