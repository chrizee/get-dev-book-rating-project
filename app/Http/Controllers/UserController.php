<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['string', 'required', 'max:191', 'min:4'],
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'api_token' => str_random(60)
        ]);
        if($user) {            
            return response()->json(['success' => true, 'user' => $user]);
        }
        return response()->json(['success' => false, 'message' => "Unable to register. Please try again"]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ]);

        $user = User::where("email", $request->input('email'))->first();
        if ($user && Hash::check($request->input('password'), $user->password)) {  
            //update the token on every login            
            $user->api_token = str_random(60);
            $user->save();     
            return response()->json(['success' => true, 'user' => $user]);
        }
        return response()->json(['success' => false, 'message' => "Wrong password"]);
    }
}
