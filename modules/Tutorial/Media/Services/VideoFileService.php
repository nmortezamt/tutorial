<?php

namespace Tutorial\Media\Services;

use Illuminate\Support\Facades\Storage;
use Tutorial\Media\Contracts\FileServiceContract;

class VideoFileService extends DefaultFileService implements FileServiceContract
{
    public static function upload($file,$filename,$dir):array
    {
        Storage::putFileAs($dir,$file, $filename . '.' . $file->getClientOriginalExtension());
        return ['video'=> $filename . '.' . $file->getClientOriginalExtension()];
    }

    public static function thumb($media)
    {
        return url('/panel/img/video.png');
    }

    public static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['video'];
    }
}
