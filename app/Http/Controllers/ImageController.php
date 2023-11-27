<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImageRequest;

class ImageController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum');
    }

    public function image(ImageRequest $request) {
        $request->validated();
        $url = Storage::disk('minio')->temporaryUrl($request->image, now()->addMinutes(5));

        return response([
            'url' => $url,
        ], 201);
    }
}
