<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Sonido;

class UserController extends Controller
{
    public function formSubmit(Request $request)
    {
        $data = [
            'message' => 'Por favor cargue un archivo'
        ];

        $file = $request->all()['file'];
        if(is_file($file)){
            if($file->extension() == "mp3"){
                $name = auth()->user()->discord_id . "." . $file->extension();
                $path = Storage::putFileAs('public_sonidos', $file, $name);

                $sonido_usuario = auth()->user()->sonido;
                if(empty($sonido_usuario)){
                    $sonido_usuario = new Sonido();
                    $sonido_usuario->usuario_id = auth()->user()->id;
                    $sonido_usuario->path_sonido = $name;
                    $sonido_usuario->tipo = "api";
                }else{
                    $sonido_usuario->path_sonido = $name;
                    $sonido_usuario->tipo = "api";
                }

                $sonido_usuario->save(); 
                $data['message'] = "Archivo guardado";
            }else{
                $data['message'] = "Tipo de archivo no valido";
            }
        }

        return response()->json($data);
    }

    public function soundByUser(Request $request)
    {
        $data = $request->all();
        $vali = Validator::make($data, [
            'sound_id' => 'required|numeric',
        ]);

        if($vali->fails()){
            return response()->json(['error'=>'parametros no permitidos']);
        }

        $user = User::where('discord_id', $data['sound_id'])->first();

        if(empty($user)){
            return response()->json(['error'=>'No se encontro informacion relacionada con los parametros proporcionados']);
        }

        $sonido = $user->sonido->path_sonido;
        $sonido = asset("public_sonidos/{$sonido}");

        return response()->json($sonido);
    }
}
