<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use DB;


use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller {
    public $successStatus = 200;

    public function __construct(){
        $this->middleware(['isAdmin']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function role_list()
    {
        //$roles = Role::all(); 
        //return response()->json(['data' => $roles], $this-> successStatus); 
       // return response()->json(Role::all(),200);
       return response()->json(Role::with('permissions')->get(),200);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function role_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles'
        ]);
        if($validator->fails()){
           // return response()->json(['error'=>$validator->errors()], 401);
           return response(['error' => 'permission name is unique and required'], 401);

        }
        $input = $request->all();
        $role = Role::create(['name' => $input['name']]);
        if($role){
            return response()->json(['role' => $role], 200); 
        }
        return response()->json(['error' => "Unable to create a role"], 401);        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permission_list()
    {
       // $permissions = Permission::all(); 
        //return response()->json(['permissions' => $permissions], $this-> successStatus); 
        return response()->json(Permission::all(),200);

    }

     public function listusers()
    {
    // return response()->json(User::whereHas("roles", function($q){ $q->where("name", "Member"); })->get());


        return response()->json(User::with('roles','permissions')->get(),200);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function permission_store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions'
        ]);
        if($validator->fails()){
            return response(['error' => 'permission name is unique and required'], 401);
        }
        $input = $request->all();
        $permission = Permission::create(['name' => $input['name']]);
        if($permission){
            return response()->json(['permission' => $permission], $this-> successStatus);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function role_has_permissions(Request $request, Role $role){
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|exists:permissions,id'
        ]);
        if($validator->fails()){
            //return response()->json(['error' => $validator->errors()], 401);
            return response(['error' => 'permission name is required'], 401);

        }
        $permission = Permission:: find($request['permission_id']);

        if($role->givePermissionTo($permission)){
            //return response()->json(['success' => $role], $this-> successStatus);
            return response(['data'=>'permission given successfuly']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assign_user_to_role(Request $request, Role $role){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }
        $user = User:: find($request['user_id']);//->firstOrFail();!! dima te5ou el id mte3ou 1 , c pour ca na7itha c pas logique

        if($user->assignRole($role)){
            //return response()->json(['success' => $user], $this-> successStatus);
            return response(['data'=>'role assgined successfuly']);
        }
    }

    public function assign_role_to_user(Request $request, user $user){
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id'
        ]);
        if($validator->fails()){
            //return response()->json(['error' => $validator->errors()], 401);
            return response(['error' => 'role name is required'], 401);

        }
        $role = Role:: find($request['role_id']);//->firstOrFail();!! dima te5ou el id mte3ou 1 , c pour ca na7itha c pas logique

        if($user->assignRole($role)){
           // return response()->json(['success' => $role], $this-> successStatus);
           return response(['data'=>'role assgined successfuly']);

        }
    }


    public function editRole($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


            return response(['message'=>'done!!']);
    }


public function updatepermissions(Request $request, $id)
{
/*
    $this->validate($request, [
        'name' => 'required|unique:permissions',
       // 'permission' => 'required',
    ]);
*/
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:permissions',
    ]);

    if($validator->fails()){
         //response()->json(['error'=>$validator->errors()], 401);
        return response(['error'=>'permissions name is required and unique'],401);

    }
    $permission=Permission::find($id);

        if(is_null($permission)){
            return response()->json(['error','permissin Not Found'],404);
        }

         //$role = Role::find($id);
         $permission->name = $request->input('name');
         $permission->save();
 
 
        // $role->syncPermissions($request->input('permission'));
 
 
         return response()->json(['success' => $permission], $this-> successStatus);
}
    

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
        ]);
    
        if($validator->fails()){
            //response()->json(['error'=>$validator->errors()], 401);
           return response(['error'=>'role name is required and unique'],401);
   
       }

        $role=Role::find($id);

        if(is_null($role)){
            return response()->json(['error','role Not Found'],404);
        }

        //$role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();


       // $role->syncPermissions($request->input('permission'));


        //return response()->json(['success' => $role], $this-> successStatus);
       // return response()->json(['data','role updated successfuly'],200);
       return response(['data'=>'role updated successfuly']);

    }


    public function destroy($id)
    {
        $role=Role::find($id);

        if(is_null($role)){
            return response()->json(['error','role Not Found'],404);
        }
        DB::table("roles")->where('id',$id)->delete();
        return response(['data'=>'deleted seccessfuly']);
    }

    public function destroyPermission($id)
    {
        $permission=Permission::find($id);

        if(is_null($permission)){
            return response()->json(['error','permission Not Found'],404);
        }
        DB::table("permissions")->where('id',$id)->delete();
        return response(['data'=>'deleted seccessfuly']);
    }

    
    public function getrole($id){
        $role=Role::find($id);
        if(is_null($role)){
            return response()->json(['error','Role Not Found'],404);
        }
        return response()->json($role::find($id),201);

    }

    public function getPermission($id){
        $permission=Permission::find($id);
        if(is_null($permission)){
            return response()->json(['error','Permission Not Found'],404);
        }
        return response()->json($permission::find($id),201);

    }
    
}
