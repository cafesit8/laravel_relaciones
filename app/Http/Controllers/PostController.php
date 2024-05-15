<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
}
