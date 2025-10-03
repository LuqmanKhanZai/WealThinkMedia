<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\Module;
use App\Models\UserManagement\SubModule;
use Spatie\Permission\Models\Permission;

class RolePrivilagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role_privialages.list')->only(['index']);
        $this->middleware('permission:role_privialages.create')->only(['assign_permission']);
    }
    
    public function index()
    {
        $roles = Role::where('status', '!=', 2)->get();
        $modules = Module::all();
        return view('admin.user_management.role_privilages.index', compact('roles', 'modules'));
    }

    public function get_permission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|integer',
            'module_id' => 'required|integer'
        ]);
        $submodule = SubModule::where('module_id', $request->module_id)->with('permissions')->get();
        $RoleHasPermission = DB::table('role_has_permissions')->where('role_id',$request->role_id)->get();
        return response()->json([
            'submodule' => $submodule,
            'RoleHasPermission' => $RoleHasPermission,
            'can_assign_permission' => auth()->user()->can('role_privialages.create')
        ]);
    }

    public function permission_list(Request $request)
    {
        $request->validate([
            'role_id' => 'required|integer',
            'module_id' => 'required|integer'
        ]);
        $submodules = SubModule::where('module_id', $request->module_id)->with('permissions')->get();
        $permissions = Permission::where('module_id', $request->module_id)->get();
        $module = Module::find($request->module_id);
        $role = Role::find($request->role_id);
        $RoleHasPermission = DB::table('role_has_permissions')->where('role_id',$request->role_id)->get();
        return view('management.role_privilages.permission', compact('submodules', 'permissions','role','module','RoleHasPermission'));
    }

    public function assign_permission(Request $request)
    {
        $request->validate([
            'module_id' => 'required|integer',
            'role_id' => 'required|integer',
            'permission' => 'required|array'
        ]);
        $skipPermission = Permission::where('module_id',$request->module_id)->pluck('id');
        $OldPermissionModule = DB::table('role_has_permissions')
        ->where('role_id',$request->role_id)->whereIn('permission_id',$skipPermission)->delete();
        $data = array();
        foreach ($request->permission as $key => $item){
            $data[$key] = (int)$item;
        }
        $RoleHasPermission = DB::table('role_has_permissions')
        ->where('role_id',$request->role_id)
        ->pluck('permission_id')->toArray();
        $mergedPermissions = array_unique(array_merge($RoleHasPermission, $data));
        if (!empty($mergedPermissions)){
            $role = Role::find($request->role_id);
            $role->syncPermissions($mergedPermissions);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Permission Assigned Successfully'
        ]);
    }
}
