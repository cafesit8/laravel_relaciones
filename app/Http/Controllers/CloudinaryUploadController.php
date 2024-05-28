<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CloudinaryUploadController extends Controller
{
  public function uploadImages(Request $request)
  {
    try {
      // Array para almacenar las URL de las imágenes subidas a Cloudinary
      $uploadedUrls = [];
      
      // Itera sobre cada imagen enviada en la petición
      foreach ($request->file('images') as $image) {
        $imageUploaded = cloudinary()->upload($image->getRealPath(), [
          'folder' => 'laravel',
          'fetch_format' => 'webp',
          /*'overwrite' => true, // Cuando 'overwrite' está configurado en true, si hay una imagen con el mismo nombre en el almacenamiento de Cloudinary
                               // se sobrescribirá con la imagen que se está subiendo en este caso.   
          'invalidate' => true,
          'tags' => 'laravel2',
          'use_filename' => true,
          'unique_filename' => false,*/
          'resource_type' => 'image',
          'secure' => true,
        ]);

        $small = $this->optimizedImage($imageUploaded->getPublicId(), ['width' => 300], 'webp');
        $medium = $this->optimizedImage($imageUploaded->getPublicId(), ['width' => 600], 'webp');
        $original = $imageUploaded->getSecurePath();
        $id = $imageUploaded->getPublicId();

        /*$info = [
          'original' => $original,
          'small' => $small,
          'medium' => $medium,
          'id' => $id
        ];*/
        
        $info = [
          'id' => $id,
          'url' => $medium
        ];

        $uploadedUrls[] = $info;
      }

      // Devuelve las URLs de las imágenes subidas como respuesta
      return response()->json(['message' => 'Images uploaded successfully', 'data' => $uploadedUrls, 'status' => 200], 200);
    } catch (\Throwable $th) {
      return response()->json(['error' => $th->getMessage(), 'code' => $th->getCode()], 500);
    }
  }

  public function optimizedImage($publicId, $options = [], $format = 'jpg') {
    $cloudName = config('cloudinary.cloud_name');
    $transformations = [];

    if (isset($options['width'])) {
        $transformations[] = 'w_' . $options['width'];
    }

    if (isset($options['crop'])) {
        $transformations[] = 'c_' . $options['crop'];
    }

    $transformationString = implode(',', $transformations);

    return "https://res.cloudinary.com/{$cloudName}/image/upload/{$transformationString}/{$publicId}.{$format}";
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
