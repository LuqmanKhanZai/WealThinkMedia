<?php

namespace App\Http\Controllers\Product;

use App\Models\Product\Item;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Configuration\Category;
use App\Http\Requests\Product\ItemRequest;

class ItemController extends Controller
{
    public function store(ItemRequest $request)
    {
        $existingItem = Item::where('item_name', $request->item_name)->first();
        if ($existingItem) {
            // return response()->exist('Item with the same name already exists');
            return response()->json([
                'msg' => 'Item with the same name already exists',
                'status' => 409,
            ]);
        }

        $run = Item::create($request->except('submit'));

        return  $run ?
            response()->success($request->item_name . ' added successfully')
            :
            response()->error($request->item_name . 'Error occured please try again');
    }

    public function update(ItemRequest $request, $id)
    {
        $data = Item::find($id);
        if (!$data) {
            return response()->json(['msgErr' => 'Item not found']);
        }
        $data = Item::find($id)->update($request->except('submit', 'item_pattern'));

        return  $data ?
            response()->success($request->item_name . ' has been updated')
            :
            response()->error($request->item_name . 'Error occured please try again');
    }

    public function destroy($id)
    {
        $run = Item::find($id);
        if (!$run) {
            return response()->json(['msg' => 'Item not found']);
        }

        // $purchaseDetails = PurchaseDetail::where('item_id', $id)->exists();
        // $saleDetails = SaleDetail::where('item_id', $id)->exists();
        // if ($purchaseDetails || $saleDetails) {
        //     return response()->error('Item cannot be deleted as it is associated with purchase or sale details.');
        // }

        $run = $run->delete();
        return  $run ?
            response()->success('Operation performed successfully')
            :
            response()->error('Error occured please try again');
    }

    // WebBlade
    public function index()
    {
        $categories = Category::all(); // Fetch all categories for the dropdown
        $rows = Item::get();
        return view('admin.item.index', get_defined_vars());
    }

    public function edit($id)
    {
        $row = Item::findOrFail($id); // Ensure the item exists, and fetch its details
        $categories = Category::all(); 
        return view('admin.item.edit', get_defined_vars());
    }

    public function status(Request $request)
    {
        $row = Item::where('id', $request->id)->first();
        $row->item_status == 1 ? $row->item_status = 0 :  $row->item_status = 1;
        $run = $row->update();
        return $rows = Item::where('id', $request->id)->first();
    }


    public function get_data()
    {
        $item = Item::get();
        return DataTables::of($item)
            ->addColumn('action', function ($item) {
                return '<div class="btn-group btn-group-xs">
                    <a href="' . route('item.edit', $item->id) . '" class="btn btn-success"><i class="fa fa-edit"></i></a>
                    <a onclick="Delete(' . $item->id . ')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}

