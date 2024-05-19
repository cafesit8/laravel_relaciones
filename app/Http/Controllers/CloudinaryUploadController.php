<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CloudinaryUploadController extends Controller
{
  public function uploadImages(Request $request)
  {
    try {
      //$result = $request->file('image')->storeOnCloudinary('laravel');

      // Array para almacenar las URL de las imágenes subidas a Cloudinary
      $uploadedUrls = [];
      
      // Itera sobre cada imagen enviada en la petición
      foreach ($request->file('images') as $image) {
        // Creamos la carpeta y subimos la imagen
        $uploadedImage = $image->storeOnCloudinary('laravel');

        // Obtenemos el ID de la imagen subida
        $imageId = $uploadedImage->getPublicId();

        // Obtenemos la URL de la imagen subida
        $imageUrl = $uploadedImage->getSecurePath();

        // Obtén el tamaño de la imagen
        $size = $uploadedImage->getSize();

        $imageInfo = [
          'id' => $imageId,
          'url' => $imageUrl,
          'size' => $size,
        ];
        $uploadedUrls[] = $imageInfo;
      }

      // Devuelve las URLs de las imágenes subidas como respuesta
      return response()->json(['message' => 'Images uploaded successfully', 'data' => $uploadedUrls, 'status' => 200], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage(), 'code' => $th->getCode()], 500);
    }
  }

  public function deleteImages(Request $request)
  {
    try {
      $imageId = $request->input('image_id');
      if(!$imageId) {
        return response()->json(['message' => 'Image ID not found', 'data' => $imageId, 'status' => 404], 404);
      }

      $result = cloudinary()->destroy($imageId);
      if(!$result) {
        return response()->json(['message' => 'Image not found', 'data' => $result, 'status' => 404], 404);
      }
      
      return response()->json(['message' => 'Image deleted successfully', 'data' => $result, 'status' => 200], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage(), 'code' => $th->getCode()], 500);
    }
  }
}
