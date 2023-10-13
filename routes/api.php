<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/login", [LoginController::class, "login"]);

Route::post("/logout", function(Request $request){
    Auth::guard('web')->logout();
    return response()->json(['message' => 'Bye bye']);
})->middleware("auth:sanctum");

Route::post("/upload", [UserController::class, "formSubmit"])->middleware("auth:sanctum");

Route::get("/getuser", function(Request $request){
    $user = auth()->user();
    $user->path_sonido = asset('public_sonidos/' . $user->sonido->path_sonido);
    unset($user->sonido);
    return response()->json($user);
})->middleware("auth:sanctum");

Route::get("/get-sound-by-user", [UserController::class, 'soundByUser']);