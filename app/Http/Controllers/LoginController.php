<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            return response()->json(['message' => 'Authenticated']);
        }
 
        return response()->json(['error' => 'No se encontro regisos en la base de datos para la información proporcionada'], 401);
    }
}
