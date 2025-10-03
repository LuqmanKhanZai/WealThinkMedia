<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user.list')->only(['index']);
        $this->middleware('permission:user.create')->only(['create', 'store']);
        $this->middleware('permission:user.update')->only(['edit', 'update']);
        $this->middleware('permission:user.delete')->only(['destroy','delete']);
    }

    public function store(Request $request)
    {
       
        DB::beginTransaction();
        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_type' => 'management',
                'role_id' => $request->role_id,
                'from_time' => $request->from_time ?? null,
                'to_time' => $request->to_time ?? null,
                'monday_login' => $request->monday_login ? 'true' : null,
                'tuesday_login' => $request->tuesday_login ? 'true' : null,
                'wednesday_login' => $request->wednesday_login ? 'true' : null,
                'thursday_login' => $request->thursday_login ? 'true' : null,
                'friday_login' => $request->friday_login ? 'true' : null,
                'saturday_login' => $request->saturday_login ? 'true' : null,
                'sunday_login' => $request->sunday_login ? 'true' : null,
                'holiday_login' => $request->holiday_login ? 'true' : null,
            ]);
            $role = Role::find($request['role_id']);
            $user->assignRole($role);

            DB::commit();
            return response()->success($request->name . ' added successfully'); // Corrected HTTP status code
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to add user. ' . $e->getMessage()
            ], 500); // Internal Server Error status
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['msgErr' => 'Record not found']);
        }
        
        $request['status'] = $request->status ? 1 : 0;
        $dataToUpdate = $request->except('submit', 'employee', 'branches');

        // Check if password is null
        if (is_null($request->password)) {
            // If password is null, remove it from the update data
            unset($dataToUpdate['password']);
        }

        $updated = $user->update($dataToUpdate);

        if ($updated && $request->role_id) {
            $role = Role::findById($request->role_id);
            $user->syncRoles([$role->name]);
        }

        return $updated ?
            response()->success('User has been updated')
            :
            response()->error('Error occurred, please try again');
    }

    public function destroy($id)
    {
        $run = User::find($id);
        if (!$run) {
            return response()->json(['msg' => 'Record not found']);
        }
        $run = $run->delete();
        return  $run ?
                response()->success('Operation performed successfully')
                :
                response()->error('Error occured please try again');
    }

    public function index()
    {
        $users = User::with('role')->where('id','!=',1)->get();
        return view('admin.user_management.user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('status', '!=', 2)->get();
        return view('admin.user_management.user.create',get_defined_vars());
    }

    public function edit($id)
    {
        if ($id == 1) {
            return redirect('/');
        } else {
            $user = User::find($id);
            $roles = Role::where('status', '!=', 2)->get();
            return view('admin.user_management.user.edit', compact('user', 'roles'));
        }
    }

}
