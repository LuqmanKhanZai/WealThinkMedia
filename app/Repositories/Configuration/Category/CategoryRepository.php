<?php

namespace App\Repositories\Configuration\Category;

use App\Models\Configuration\Category;
use App\Models\Product\Item;

class CategoryRepository implements CategoryInterface
{

    public function storeCategory($request)
    {
        $run = Category::create($request->except('submit'));
        return  $run ?
            response()->success($request->category_name . ' added successfully')
            :
            response()->error($request->category_name . 'Error occured please try again');
    }
    
    public function findCategory($id)
    {
        $data = Category::find($id);
        if (!$data) {
            return response()->json(['msgErr' => 'Category not found']);
        }
        return response()->json($data);
    }

    
    public function updateCategory($request, $id)
    {
        $data = Category::find($id);
        if (!$data) {
            return response()->json(['msgErr' => 'Category not found']);
        }
        $data = Category::find($id)->update($request->except('submit'));

        return  $data ?
            response()->success($request->category_name . ' has been updated')
            :
            response()->error($request->category_name . 'Error occured please try again');
    }

   

    public function destroyCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $exists = Item::where('category_id', $category->id)->exists();
        if ($exists) {
            return response()->json(['message' => 'Category With Items cannot be deleted'], 500);
        }

        $deleted = $category->delete();
        return $deleted ?
            response()->json(['message' => 'Operation performed successfully']) :
            response()->json(['message' => 'Failed to delete category'], 500);
    }


    public function statusCategory($request)
    {
        $row = Category::where('id', $request->id)->first();
        $row->category_status == 1 ? $row->category_status = 0 :  $row->category_status = 1;
        $run = $row->update();
        return $rows = Category::where('id', $request->id)->first();
    }
}
