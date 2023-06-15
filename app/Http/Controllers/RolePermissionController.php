<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    private $guard_name = "web";

    public function roleStore(Request $request){
        $validation = Validator::make($request->all(),[
            'roleName'=>'required',
            'permissions'=>'required',
        ]);

        if($validation->fails())return response()->json(['errors'=>$validation->errors()->all()],400);

        $roleName = $request->roleName;
        $permissions = $request->permissions;
        try {
            $role = Role::create(['name'=>$roleName,'guard_name'=>$this->guard_name]);
            $role->syncPermissions($permissions);
            return response()->json($role);
            
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('RolePermissionController roleCreate method : ',$th->getTrace());
            return response()->json(['message'=>'Role Permission already exists!']);
        }
    }

    public function roleIndex(){
        $roles = Role::all();
        foreach ($roles as $role) {
            $role->permissions = $role->permissions()->pluck('name');
        }

        return response()->json($roles);
    }

    public function roleEdit($id){
        $role = Role::find($id);
        $role->permissions = $role->permissions()->pluck('name');

        return response()->json($role);
    }

    public function roleUpdate(Request $request,$id){
        $validation = Validator::make($request->all(),[
            'permissions'=>'required',
        ]);

        if($validation->fails())return response()->json(['errors'=>$validation->errors()->all()],400);

        $permissions = $request->permissions;

        try {
            $role = Role::find($id);
            $role->syncPermissions($permissions);
            return response()->json($role);
    
            return response()->json(['message'=>'Role deleted successfully!']);
            
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('RolePermissionController roleDestroy method : ',$th->getTrace());
            return response()->json(['message'=>'Something wrong! try again']);
        }
    }
    public function roleDestroy($id){
        try {
            $role = Role::where('id',$id)->delete();
    
            return response()->json(['message'=>'Role deleted successfully!']);
            
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('RolePermissionController roleDestroy method : ',$th->getTrace());
            return response()->json(['message'=>'Something wrong! try again']);
        }
    }

    public function permissionStore(Request $request){
        $validation = Validator::make($request->all(),[
            'permissionName'=>'required',
            'operations'=>'required'
        ]);
        if($validation->fails())return response()->json(['errors'=>$validation->errors()->all()],400);

        $permissionName = $request->permissionName;
        $operations = $request->operations;
        
        try {
            $permissions = [];
            foreach ($operations as $operation) {
                $permission = Permission::create(['name'=>strtolower($permissionName).'-'.strtolower($operation),'guard_name'=>$this->guard_name]);
                $permissions[] = $permission->name;
            }

            return response()->json($permissions);
            
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('RolePermissionController permissionStore method : ',$th->getTrace());
            return response()->json(['message'=>'Permission already exists!']);
        }

    }

    public function permissionIndex(){
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function permissionDestroy($id){
        try {
            $role = Permission::where('id',$id)->delete();
    
            return response()->json(['message'=>'Permission deleted successfully!']);
            
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('RolePermissionController permissionDestroy method : ',$th->getTrace());
            return response()->json(['message'=>'Something wrong! try again']);
        }
    }
}
