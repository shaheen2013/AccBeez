<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Events\SendVerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendVerificationCodeMail;
use App\Events\EmailVerificationCodeEvent;
use Spatie\Permission\Models\Role;

use function PHPSTORM_META\type;

class UserController extends Controller
{
    // public function __construct()
    // {
    //    $this->middleware('auth');
    // }

    public function index()
    {
        // dd('hi index');
        $users = User::all();
        $roles = Role::all()->pluck('name');
        foreach($users as $user){
            if($user->hasAnyRole($roles->toArray())){
                $user->role = $user->getRoleNames()[0];
            }
        }
        // dd(Auth::check(), auth());

        // Return the users as a response
        return response()->json($users);
    }

    public function store(UserRequest $request)
    {
        // dd(config('app.url'));
        try {
            $userData = $request->only('name', 'email','role');

            DB::beginTransaction();
            $userData['invitation_token'] = random_int(100000, 999999);
            $userData['password'] = Hash::make('123456');
            $userData['role'] = 'user';
            $user = User::create($userData);
            // event(new SendVerificationCode($user->email, $user->invitation_token));
            // dd('store', $request->all(), $userData);
            DB::commit();
            SendVerificationCode::dispatch($user['email'], $user['invitation_token']);
            return $user;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }


    public function edit($id)
    {
        // dd('hi index');
        $user = User::find($id);

        // Return the customers as a response
        return response()->json($user);
    }


    public function update(UserRequest $request, $id)
    {
        try {
            $userData = $request->only('name', 'email','role');
            $user = User::find($id);
            $user->update($userData);
            return $user;
        } catch (Exception $ex) {
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            $users = User::all();
            return response()->json($users);
        } catch (Exception $ex) {
            return 'Delete Failed';
        }
    }

    public function confirm_registration(Request $request)
    {
        // dd($request->all(), request(), 'hi');
        $user = User::where('email', $request->email)
                    ->where('invitation_token', $request->code)
                    ->first();
        return view('register.register', compact('user'));
        // $request->get('invitee');
    }

    public function update_registration(Request $request)
    {
        $user = User::where('email', $request->email)
                    ->where('invitation_token', $request->invitation_token)
                    ->first();
                    // dd($request->all(), $user);

        $user->password = Hash::make($request->password);
        $user->markEmailAsVerified();
        $user->save();
        return redirect()->route('login');
    }


    public function logged_in_user(Request $request)
    {
        $logged_in_user = Auth::user();
        $logged_in_user = User::find($logged_in_user->id);
        $logged_in_user->role = $logged_in_user->getRoleNames()[0];
        return response()->json($logged_in_user);
    }

}
