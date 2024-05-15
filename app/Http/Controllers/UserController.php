<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  function index() {
    try {
      $users = User::with('photoProfile')->paginate(5);
      // $post = Post::find(1);
      // $users = User::with('posts')->with('photoProfile')->paginate(5); // Me devuelve los usuarios con sus posts y photoProfile
      $response = [
        'message' => 'Usuarios Encontrados',
        'response' => $users,
        'status' => 200
      ];
      return response()->json($response, 200);
    } catch (\Throwable $th) {
      $response = [
        'message' => 'Error al buscar usuarios',
        'response' => $th->getMessage(),
        'status' => 500
      ];
      return response()->json($response, 500);
    }
  }

  function show($id) {
    try {
      $user = User::find($id);

      if (!$user) {
        $response = [
          'message' => 'Usuario no encontrado',
          'status' => 404
        ];
        return response()->json($response, 404);
      }

      $response = [
        'message' => 'Usuario encontrado',
        'response' => $user,
        'status' => 200
      ];

      return response()->json($response, 200);
    } catch (\Throwable $th) {
      $response = [
        'message' => 'Error al buscar usuario',
        'data' => $th->getMessage(),
        'status' => 500
      ];
      return response()->json($response, 500);
    }
  }

  function store (Request $request) {
    $validator = Validator::make($request->all(), [
      'name' => 'required|max:100',
      'surname' => 'required|max:200',
      'age' => 'required|numeric',
      'email' => 'required|unique:users|email',
    ]);

    if($validator->fails()) {
      $response = [
        'message' => 'Error de validación',
        'data' => $validator->errors(),
        'status' => 422
      ];
      return response()->json($response, 422);
    }

    $newUser = User::create($request->all());

    if(!$newUser) {
      $response = [
        'message' => 'Error al crear usuario',
        'status' => 500
      ];
      return response()->json($response, 500);
    }

    $response = [
      'message' => 'Usuario creado',
      'response' => $newUser,
      'status' => 201
    ];
    
    return response()->json($response, 201);
  }

  function destroy($id) {
    $user = User::find($id);

    if(!$user) {
      $response = [
        'message' => 'Usuario no encontrado',
        'status' => 404
      ];
      return response()->json($response, 404);
    }

    $user->delete();

    $response = [
      'message' => 'Usuario eliminado',
      'status' => 200
    ];

    return response()->json($response, 200);
  }

  function update (Request $request, $id) {
    $user = User::find($id);
    if(!$user) {
      $response = [
        'message' => 'Usuario no encontrado',
        'status' => 404
      ];
      return response()->json($response, 404);
    }

    $validator = Validator::make($request->all(), [
      'name' => 'max:100',
      'surname' => 'max:200',
      'age' => 'numeric',
      'email' => 'unique:users|email',
    ]);

    if($validator->fails()) {
      $response = [
        'message' => 'Error de validación',
        'data' => $validator->errors(),
        'status' => 422
      ];
      return response()->json($response, 422);
    }

    $user->update($request->all());

    $response = [
      'message' => 'Usuario actualizado',
      'response' => $user,
      'status' => 200
    ];

    return response()->json($response, 200);
  }
}
