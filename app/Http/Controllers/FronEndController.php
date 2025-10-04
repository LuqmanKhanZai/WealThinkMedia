<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function add_user(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact' => 'nullable|string|max:15',
        ]);
        
        // create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => bcrypt($request->email), // default password
            'account_type' => 'User',
        ]);
        if (!$user) {
            return response()->json(['msgErr' => 'Failed to add user'], 500);
        }
        return response()->json(['msg' => 'User added successfully']);
    }
}
