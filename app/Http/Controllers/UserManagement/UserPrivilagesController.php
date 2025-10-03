<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\Module;
use App\Models\UserManagement\SubModule;
use Spatie\Permission\Models\Permission;

class UserPrivilagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user_privialages.list')->only(['index']);
        $this->middleware('permission:user_privialages.create')->only(['assign_permission']);
    }
    public function index()
    {
        $users = User::where('role_id', '!=', 1)->get();
        $modules = Module::all();
        return view('admin.user_management.user_privilages.index', compact('users', 'modules'));
    }

    public function get_permission(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'module_id' => 'required|integer'
        ]);
        $user = User::find($request->user_id);
        $submodule = SubModule::where('module_id', $request->module_id)->with('permissions')->get();
        // $RoleHasPermission = DB::table('role_has_permissions')->where('role_id',$user->role_id)->get();
        // $UserHasPermission = DB::table('model_has_permissions')->where('model_id',$user->id)->get();
        // return response()->json([
        //     'submodule' => $submodule,
        //     'RoleHasPermission' => $RoleHasPermission,
        //     'UserHasPermission' => $UserHasPermission
        // ]);
        $RoleHasPermission = DB::table('role_has_permissions')
            ->where('role_id', $user->role_id)
            ->pluck('permission_id')
            ->toArray();

        $UserHasPermission = DB::table('model_has_permissions')
            ->where('model_id', $user->id)
            ->pluck('permission_id')
            ->toArray();

        // Merge and remove duplicates
         $allPermissions = array_unique(array_merge($RoleHasPermission, $UserHasPermission));
        // Ensure that the keys are reset, and it returns a flat array
        $allPermissions = array_values($allPermissions);
        return response()->json([
            'submodule' => $submodule,
            'RoleHasPermission' => $allPermissions,
            'can_assign_permission' => auth()->user()->can('user_privialages.create')
        ]);
    }

    public function permission_list(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'module_id' => 'required|integer'
        ]);

        $user = User::find($request->user_id);
        $submodules = SubModule::where('module_id', $request->module_id)->with('permissions')->get();
        $permissions = Permission::where('module_id', $request->module_id)->get();
        $module = Module::find($request->module_id);
        $role = Role::find($user->role_id);
        $RoleHasPermission = DB::table('role_has_permissions')->where('role_id',$user->role_id)->get();
        return view('management.user_privilages.permission', compact('submodules', 'permissions','role','module','RoleHasPermission','user'));
    }

    // public function assign_permission(Request $request)
    // {
    //     $request->validate([
    //         'role_id' => 'required|integer',
    //         'permission' => 'required|array'
    //     ]);
    //     $role = Role::find($request->role_id);
    //     $permissions = Permission::whereIn('id', $request->permission)->get();
    //     if ($permissions->isEmpty()) {
    //         $role->permissions()->detach();
    //     } else {
    //         $role->permissions()->sync($permissions);
    //     }
    //     return redirect()->route('user_privilages.index')->with('success', 'Permission Assigned Successfully');
    // }
    public function assign_permission(Request $request)
    {
        $request->validate([
            'module_id' => 'required|integer',
            'user_id' => 'required|integer',
            'permission' => 'required|array'
        ]);

        $skipPermission = Permission::where('module_id',$request->module_id)->pluck('id');
        DB::table('model_has_permissions')
        ->where('model_id',$request->user_id)->whereIn('permission_id',$skipPermission)->delete();

        $data = array();
        foreach ($request->permission as $key => $item){
            $data[$key] = (int)$item;
        }
      
        $user = User::find($request->user_id);
        $user->givePermissionTo($data);

        return response()->json([
            'status' => 200,
            'message' => 'Permission Assigned Successfully'
        ]);

        // return "000000000";
        // $RoleHasPermission = DB::table('role_has_permissions')
        // ->where('role_id',$user->role_id)
        // ->pluck('permission_id')->toArray();
        // $mergedPermissions = array_unique(array_merge($RoleHasPermission, $data));
        // if (!empty($mergedPermissions)){
        //     $role = Role::find($user->role_id);
        //     $role->syncPermissions($mergedPermissions);
        // }
        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Permission Assigned Successfully'
        // ]);
    }
}
