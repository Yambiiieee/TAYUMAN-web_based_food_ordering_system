<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendForgotPassword;
use Carbon\Carbon; 
use Hash;

class UserController extends Controller
{
    use HttpResponses;

         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsersByType(Request $request)
    {
       
        $user = auth()->user();

        $limit = $request->input('limit');

        $type = $request->input('type');

        $status = $request->input('status');

        $search=$request->search;

        $users = DB::table('users as u')
            ->where('u.type', '=',$type)
            // ->when($type, function ($query) use ($type) {
            //     return $query->where('u.type', '=',$type);
            // })
            ->when($status, function ($query) use ($status) {
                    return $query->where('u.status', '=',$status);
            })
            
            ->when($limit, function ($query) use ($limit) {
                return $query->take($limit);
            })
            ->orderBy('u.created_at', 'desc');
            
            if($search !== "") {
                $users  =  $users ->where(function ($query) use ($search){
                    $query
                    ->where('u.first_name', 'like',"%$search%")
                    ->orWhere('u.last_name', 'like',"%$search%")
                    ->orWhere('u.email', 'like',"%$search%") ;   
                });
             }

        // if($search !== "") {
        //     $users=$users
        //     ->where('u.first_name', 'like',"%$search%")
        //     ->orWhere('u.last_name', 'like',"%$search%")
        //     ->orWhere('u.email', 'like',"%$search%") ;   
        // }
        // $users=$users->when($search, function ($query) use ($search) {
        //     return $query
        //             ->where('u.first_name', 'like','%' . $search .'%')
        //             ->orWhere('u.last_name', 'like','%' . $search .'%')
        //             ->orWhere('u.email', 'like','%' . $search .'%');   
        // });    
        $users=$users->get();
        return $this->success(["users" =>  $users], "", 200);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'type' => 'required|string',
            'gender' => 'required|string',
            'contact' => 'required|string',
            'department' => 'required',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);
        

        $checkEmailExist= DB::table('users')
        ->where('email', '=',$data["email"])->count();

        if($checkEmailExist){
            return response([
                'error' => ["email"=>'Email already taken']
            ], 400);
        }
        

        if (!$request->hasFile('profile_picture')) {
            return $this->error('', 'Please upload file', 404);
        } 
        $file = $request->file('profile_picture')->store('profiles', 'public_uploads');
       
        $data['profile_picture'] = asset('uploads/'.$file);
         
        

        /** @var \App\Models\User $user */
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_initial' => $request->middle_initial,
            'type' => $data['type'],
            'gender' => $data['gender'],
            'contact' => $data['contact'],
            'department' => $data['department'],
            'profile_picture' => $data['profile_picture'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table('users')->where('id', '=',$id)->get();
        
        return $this->success(["user" =>  $user[0]], "", 200);  
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, $id){

        $idHolder=$id;
       
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'contact_number' => 'required|string',
            'email' => 'required',
            
        ]);

        $checkEmailExist= DB::table('users')
        ->where('id', '!=',$idHolder)->get()
        ->where('email', '=',$data["email"])->count();

        if($checkEmailExist){
            return response([
                'error' => ["email"=>'Email already taken']
            ], 400);
        }
       
         DB::table('users')
        ->where('id', $id)
        ->update([
            'id_number' => $request->id_number,
            'store_name' => $request->store_name,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'contact_number' => $data['contact_number'],
            'email' => $data['email'],
        ]);
    
        $user = DB::table('users')->where('id', '=',$idHolder)->get();
        
        return $this->success(["user" =>  $user[0]], "", 200);  
            
    }


      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivateUser(Request $request, $id)
    {
        $idHolder=$id;
       
       
        $checkUserExist= DB::table('users')
        ->where('id', '=',$idHolder)
        // ->get()
        ->count();

        if($checkUserExist==0){
            return $this->error($checkUserExist, 'Record not found', 404);
        }
       
        DB::table('users')
        ->where('id', $id)
        ->update([
            'is_active' => 0,
        ]);

        
        DB::table('personal_access_tokens')
        ->where('tokenable_id', $id)
        ->delete();


        // $user = User::find($id)->firstOrFail();

        // $user->tokens()->delete();

        return $this->success([], "Account Deactivated successfully", 200);

    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activateUser(Request $request, $id)
    {
        $idHolder=$id;
       
       
        $checkUserExist= DB::table('users')
        ->where('id', '=',$idHolder)
        // ->get()
        ->count();

        if($checkUserExist==0){
            return $this->error($checkUserExist, 'Record not found', 404);
        }
       
        DB::table('users')
        ->where('id', $id)
        ->update([
            'is_active' => 1,
        ]);

        
      

        return $this->success([], "Account activated successfully", 200);

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
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
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

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   $idHolder=$id;
        
        if($id!=0){
            
            /**Delete data */
            DB::table('users')
            ->where('id', $id)
            ->delete();

            DB::table('personal_access_tokens')
            ->where('tokenable_id', $id)
            ->delete();

            //  /**Delete data */
            // DB::table('event_registrations')
            //  ->where('user_id', $id)
            //  ->delete();
            
            // DB::table('events')
            //  ->where('posted_by', $id)
            //  ->delete();
                   
            return $this->success([], "Deleted successfully", 200);
        }

        return $this->error('', 'Record not found', 404);
    }
     /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendEmail()
    {
    
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = DB::table('users')
        ->where('email',$request->email)
        ->get();

       
        if(empty($user)){
            return $this->error('', 'Record not found', 404);
        }
        
        // $token = Str::random(64);
        $token = random_int(100000, 999999);
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' =>  $token, 
            'created_at' => Carbon::now()
          ]);
         

          Mail::to($request->email)
          ->send(new SendForgotPassword($user[0]->first_name,$user[0]->email, $token));

       
        return $this->success( [], "We have e-mailed your password reset link!", 200);

       
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $auth_user = auth()->user();
        
        $user = User::where('id',  $auth_user->id)
                    ->update(['password' =>  bcrypt($request->password)]);

        return $this->success( [], "Your password has been changed!", 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
                            ->where([
                              'email' => $request->email, 
                              'token' => $request->token,
                              
                            ])
                            ->where('created_at', '>=', Carbon::now()->subMinutes(5)->toDateTimeString())
                            ->orderBy('id', 'desc')
                            ->first();

        if(!$updatePassword){
            return $this->error('', 'Invalid token', 404);
        }

        $user = User::where('email', $request->email)
                    ->update(['password' =>  bcrypt($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return $this->success( [], "Your password has been changed!", 200);
    }

    public function registerUser(Request $request)
    {
          
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
            'is_active' => 1,
            'contact_number' => $data['contact_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        
        return $this->success(["user" =>  $user], "", 200);

    }
}
