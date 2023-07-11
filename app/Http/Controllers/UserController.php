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
use App\Models\UserAssign;
use App\Notifications\UserAuthenticationNotification;
use Illuminate\View\View;
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
        $users = User::latest()->get();
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
            $userData = $request->only('name', 'email','role', 'user_type');

            DB::beginTransaction();
            $userData['invitation_token'] = $this->generateToken();
            $userRole = $request->user_type ? $request->user_type : 'User';
            $user = User::create($userData);
            $user->assignRole($userRole);
            $user->notify(new UserAuthenticationNotification($user->name,$user->email,$user->invitation_token));
            DB::commit();
            return $user;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    private function generateToken()
    {
        return md5(rand(1, 10) . microtime());
    }


    public function edit($id)
    {
        // dd('hi index');
        $user = User::find($id);
        $roles = $user->getRoleNames();
        // Return the customers as a response
        return response()->json([
            'user' => $user,
            'roles' => $roles
        ]);
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

    public function getUsersByRole(){
        $admins = User::whereHas('roles', function($query){
            $query->where('name', 'Admin');
        })->get();
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'User');
        })->get();

        return response()->json(
        [
            'admins' => $admins,
            'users' => $users
        ]);
    }

    public function assignUser(Request $request) {
        $admin = $request->admin;
        $users = $request->user;

        // admin create
        UserAssign::create([
            'admin_id' => $admin,
            'user_id' => null,
            'user_type' => 'Admin'
        ]);
        // users create
        foreach($users as $user_id){
            UserAssign::create([
                'admin_id' => $admin,
                'user_id' => $user_id,
                'user_type' => 'User'
            ]);
        }

        return "inserted successfully";
    }

    public function logged_in_user(Request $request)
    {
        $logged_in_user = Auth::user();
        $logged_in_user = User::find($logged_in_user->id);
        $logged_in_user->role = $logged_in_user->getRoleNames()[0];
        return response()->json($logged_in_user);
    }

    /**
     * Show the password Store page.
     * @param Request $request
     * 
     * @return View
     */
    function showPasswordPage(Request $request) :View
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }
        if (User::where('invitation_token', request()->token)->doesntExist()) {
            abort(401);
        }

        return view('auth.password', ['request' => $request]);
    }

    /**
     * Store the password.
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    function passwordStore(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed',
        ]);
        $user = User::where('invitation_token', request()->token)->first();
        $user->password = Hash::make(request()->password);
        $user->invitation_token = null;
        $user->status = 1;
        $user->save();
        return redirect()->route('login');    
    }

}
