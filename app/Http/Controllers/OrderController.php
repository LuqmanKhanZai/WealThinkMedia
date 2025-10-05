<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderToItem;
use App\Models\Product\Item;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->get();
        return view('admin.order.index', get_defined_vars());
    }


    public function print($id)
    {
        $order = Order::with('user')->findOrFail($id);
        $items = OrderToItem::where('order_id', $id)->get()->map(function ($orderItem) {
             $itemDetails = Item::find($orderItem->item_id);
             if ($itemDetails) {
                 $orderItem->item_name = $itemDetails->item_name;
                 $orderItem->price = $itemDetails->price;
                 $orderItem->description = $itemDetails->description;
                 $orderItem->type = $itemDetails->type == 1 ? 'Main' : 'Bomper';
             } else {
                 $orderItem->item_name = 'N/A';
                 $orderItem->price = 'N/A';
                 $orderItem->description = 'N/A';
                 $orderItem->type = 'N/A';
             }
            return $orderItem;
        });
        return response()->json([
            'order' => $order,
            'items' => $items,
        ]);
        return view('admin.order.print', get_defined_vars());
    }
    
}
