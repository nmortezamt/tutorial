<?php

namespace Tutorial\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Course\Models\Course;
use Tutorial\Media\Models\Media;
use Tutorial\Media\Services\MediaFileService;

class MediaController extends Controller
{
    public function download(Media $media, Request $request)
    {
        // $this->authorize('download',Course::class);
        if(!$request->hasValidSignature()){
            abort(401);
        }
        return MediaFileService::stream($media);
    }
}
