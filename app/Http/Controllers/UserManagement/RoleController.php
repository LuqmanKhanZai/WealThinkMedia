<?php

namespace App\Http\Controllers\UserManagement;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\RoleRequest;

class RoleController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permission:role.list')->only(['index']);
        $this->middleware('permission:role.create')->only(['store']);
        $this->middleware('permission:role.update')->only(['update']);
        $this->middleware('permission:role.delete')->only(['destroy']); 
    }

    public function store(RoleRequest $request)
    {
        $run = Role::create($request->except('submit'));
        return  $run ?
                response()->success($request->name.' added successfully')
                :
                response()->error($request->name.'Error occured please try again');
    }

    public function update(RoleRequest $request, $id)
    {
        $data = Role::find($id);
        if (!$data) {
            return response()->json(['msgErr' => 'Role not found']);
        }
        $data = Role::find($id)->update($request->except('submit'));

        return  $data ?
                response()->success($request->name. ' has been updated')
                :
                response()->error($request->name.'Error occured please try again');
    }

    public function destroy($id)
    {
        // Step 1: Check if the category exists
         $role = Role::find($id);
         if (!$role) {
             return response()->json(['message' => 'Role not found'], 404);
         }
     
         // Step 2: Check if there are associated items
        //  $itemsCount = Item::where('category_id', $category->id)->count();
        //  if ($itemsCount > 0) {
        //      return response()->json(['message' => 'Role With Items cannot be deleted'], 500);
        //  }
     
         // Step 3 & 5: Delete the category if no associated items found
         $deleted = $role->delete();
     
         // Step 6: Return appropriate response
         return $deleted ?
             response()->json(['message' => 'Operation performed successfully']) :
             response()->json(['message' => 'Failed to delete category'], 500);
    }

    public function status(Request $request)
    {
        $row = Role::where('id',$request->id)->first();
        $row->status == 1 ? $row->status = 0 :  $row->status = 1;
        $run = $row->update();
        return $rows = Role::where('id',$request->id)->first();
    }

    // WebBlade
    public function index()
    {
        return view('admin.user_management.role.index');
    }
    public function get_data()
    {
        $role = Role::where('status', '!=', 2)
            ->select(['id', 'name', 'status', 'created_at']);

        return DataTables::of($role)
            ->addColumn('action', function ($role) {
                $btns = '<div class="btn-group btn-group-xs">';

                if (auth()->user()->can('role.update')) {
                    $btns .= '<a onclick="ModelShow('.$role->id.', \''.$role->name.'\')" 
                                data-toggle="modal" data-target="#ModelShow" 
                                class="btn btn-success">
                                <i class="fa fa-edit"></i>
                            </a>';
                }

                if (auth()->user()->can('role.delete')) {
                    $btns .= '<a onclick="Delete('.$role->id.')" 
                                class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>';
                }

                $btns .= '</div>';

                return $btns;
            })
            ->rawColumns(['action'])
            ->make(true);
    }



    // public function get_data()
    // {
    //         //    <a onclick="Status('.$role->id.')" class="btn btn-warning"><i class="fa fa-ban"></i></a>
    //     $role = Role::where('status', '!=', 2)->select(['id', 'name', 'status', 'created_at']);

    //     return DataTables::of($role)
    //         ->addColumn('action', function ($role) {
    //             return '<div class="btn-group btn-group-xs">
    //             @can('role.update')
    //             <a onclick="ModelShow('.$role->id.', \''.$role->name.'\')" data-toggle="modal" data-target="#ModelShow" class="btn btn-success"><i class="fa fa-edit"></i></a>
    //             @endcan
    //             @can('role.delete')
    //             <a onclick="Delete('.$role->id.')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
    //             @endcan
    //                 </div>';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }

}
