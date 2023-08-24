<?php

namespace Tutorial\Media\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Tutorial\Media\Contracts\FileServiceContract;

class ImageFileService extends DefaultFileService implements FileServiceContract
{
    protected static $sizes = ['300', '600','450'];
    public static function upload($file,$filename,$dir):array
    {
        Storage::putFileAs($dir,$file, $filename . '.' . $file->getClientOriginalExtension());
        $path = $dir. $filename . '.' . $file->getClientOriginalExtension();
        return self::resize(Storage::path($path), $dir, $file->getClientOriginalExtension(), $filename);
    }

    private static function resize($img, $dir, $extension, $filename)
    {
        $image = Image::make($img);
        $imgs['original'] = $filename .'.'. $extension;
        foreach (self::$sizes as $size) {
            $imgs[$size] = $filename . '_' . $size .'.'. $extension;
            $image->resize($size, null, function ($aspect) {
                $aspect->aspectRatio();
            })->save(Storage::path($dir) . $filename . '_' . $size .'.'. $extension);
        }
        return $imgs;
    }

    public static function thumb($media)
    {
        return '/storage/' . $media->files[300];
    }

    public static function getFileName()
    {
        return (static::$media->is_private ? 'private/' : 'public/') . static::$media->files['original'];
    }

}
