<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
  function index() {
    try {
      $posts = Post::with(['image', 'user', 'likes'])->paginate(5);

      if (!$posts) {
        return response()->json([
          'message' => 'No posts found',
          'status' => 404
        ], 404);
      }

      $response = [
        'message' => 'Posts retrieved successfully',
        'response' => $posts,
        'status' => 200
      ];

      return response()->json($response, 200);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Error al buscar usuarios',
        'response' => $th->getMessage(),
        'status' => 500
      ], 500);
    }
  }

  function store(Request $request) {
    try {
      $validator = Validator::make($request->all(), [
        'description' => 'required|string|max:1000',
        'title' => 'required|string|max:150',
        'date' => 'required|date',
        // 'images' => 'required|array|min:1|max:5',  // <--- Aquí es para guardar imagenes al momento de crear un post
        'user_id' => 'required|exists:users,id'
      ]);
  
      if ($validator->fails()) {
        return response()->json([
          'message' => 'Validation error',
          'response' => $validator->errors(),
          'status' => 422
        ], 422);
      }
  
      $post = Post::create($request->all());

      if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('images');
            $post->images()->create(['path' => $path]);
        }
      }
  
      if (!$post) {
        return response()->json([
          'message' => 'Error al crear el post',
          'status' => 500
        ], 500);
      }
  
      $response = [
        'message' => 'Post created successfully',
        'response' => $post,
        'status' => 201
      ];
  
      return response()->json($response, 201);
    } catch (\Throwable $th) {
      return response()->json([
        'message' => 'Error al crear el post',
        'response' => $th->getMessage(),
        'status' => 500
      ], 500);
    }
  }
}
