<?php

namespace App\Http\Controllers;

use App\Models\Product\Item;
use Illuminate\Http\Request;

class FronEndController extends Controller
{
    public function product_list(Request $request)
    {
        $items = Item::select('id', 'item_name', 'price', 'description', 'type')->get()->map(function ($item) {
            $item->product_type = $item->type == 1 ? 'Main' : 'Bomper';
            return $item;
        });
        return response()->json($items);
    }
}
