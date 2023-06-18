<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\Rules\Password;
use App\Mail\SendForgotPassword;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;

use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Carbon\Carbon; 

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    use HttpResponses;

    public function register(Request $request)
    {
        /**Check if otp is valid */
        $checkCode = DB::table('verify_emails')
        ->where([
          'email' => $request->email, 
          'token' => $request->token,
          
        ])
        ->where('created_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString())
        ->orderBy('id', 'desc')
        ->first();
        

        if(!$checkCode){
            return $this->error('', 'Invalid token', 404);
        }
       

        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'type' => 'required|string',
            'contact_number' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);

        /** @var \App\Models\User $user */
        $user = User::create([
            'id_number' => $request->id_number,
            'store_name' => $request->store_name,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'type' => $data['type'],
            'contact_number' => $data['contact_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        
        $token = $user->createToken('main')->plainTextToken;

        DB::table('verify_emails')->where(['email'=> $request->email])->delete();


        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
            ],
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);





        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 400);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if($user->is_active==0){
            return response([
                'error' => 'No active account found with credentials'
            ], 404);
        }

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function logout()
    {
        /** @var User $user */
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    public function verifyEmail(Request $request){
        
        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required',
        ]);

        $token = random_int(100000, 999999);
        
         

          Mail::to($request->email)
          ->send(new VerifyEmail($request->first_name,$request->email, $token));
          
          DB::table('verify_emails')->insert([
            'email' => $request->email, 
            'token' =>  $token, 
            'created_at' => Carbon::now()
          ]);
       
        return $this->success( [], "We have e-mailed your password reset link!", 200);
    }

}
