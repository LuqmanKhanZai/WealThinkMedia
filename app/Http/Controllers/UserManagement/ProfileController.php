<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.user_management.profile.index');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',  // Add minimum password length
        ]);
        // Get the authenticated user
        $user = Auth::user();

        // Check if the old password matches the user's current password
        if (!Hash::check($request->old_password, $user->password)) {
            response()->error('Old password is incorrect');
        }

        // Update the password
        $run = User::where('id', $user->id)->update([
            'password' => bcrypt($request->password),
        ]);
        // Logout the user
        Auth::guard('web')->logout();
        // Return success or error response
        return  $run ?
        response()->success('Password updated successfully')
        :
        response()->error('Error occurred, please try again');
    }
}
