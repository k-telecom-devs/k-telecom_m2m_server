<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

            if ($user->save()) {
                return $this->login($request);
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

    public function confirm(): JsonResponse
    {
        try{
            $u = auth()->user();
    
            $user = User::find($u['id']);
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

    public function login(Request $request): JsonResponse
    {
        $email = $request->email;
        $password = $request->password;

        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        $credentials = request(['email', 'password']);

        auth()->factory()->setTTL(43200); 

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function profile_change(Request $request): JsonResponse
    {   try{
        $u = auth()->user();

        $user = User::find($u['id']);

        $user->phone_number = $request->phone_number;
        $user->notifications = $request->notifications;
        $user->auto_update = $request->auto_update;
        $user->auto_pay = $request->auto_pay;
        $user->email = $request->email;
        $user->name = $request->name;

        if ($user->save()){
            return response()->json(['message' => 'Data updated successfully']);
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
        auth()->factory()->setTTL(1);
        return response()->json(['token' => auth()->fromUser(auth()->user())]);
    }
}
