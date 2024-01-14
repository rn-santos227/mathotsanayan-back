<?php

namespace App\Http\Controllers\Shared;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImageRequest;

class ImageController extends Controller
{
  public function __construct() {
    $this->middleware('auth:sanctum');
  }

  public function image(ImageRequest $request) {
    $request->validated();
    $url = Storage::disk('minio')->temporaryUrl($request->image, now()->addMinutes(30));

    return response([
      'url' => $url,
    ], 201);
  }
}
