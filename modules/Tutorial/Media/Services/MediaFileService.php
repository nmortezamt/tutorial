<?php

namespace Tutorial\Media\Services;

use Tutorial\Media\Contracts\FileServiceContract;
use Tutorial\Media\Models\Media;

class MediaFileService
{
    private static $file;
    private static $dir;
    private static $isPrivate;
    public static function publicUpload($file)
    {
        self::$file = $file;
        self::$dir = "public/";
        self::$isPrivate = false;
        return self::upload();
    }

    public static function privateUpload($file)
    {
        self::$file = $file;
        self::$dir = "private/";
        self::$isPrivate = true;
        return self::upload();
    }

    private static function upload()
    {
        $extension = self::normalizeExtension(self::$file);
        foreach(config('MediaFile.MediaTypeServices') as $type => $service){
            if(in_array($extension,$service['extensions'])){
                return self::uploadByHandler(new $service['handler'],$type);
            }
        }

    }

    public static function stream(Media $media)
    {
        foreach(config('MediaFile.MediaTypeServices') as $type => $service)
        {
            if($media->type == $type){
                return $service['handler']::stream($media);
            }
        }
    }

    public static function delete(Media $media)
    {
        foreach(config('MediaFile.MediaTypeServices') as $type => $service)
        {
            if($media->type == $type){
                return $service['handler']::delete($media);
            }
        }
    }

    private static function uploadByHandler(FileServiceContract $service,$type):Media
    {

        $media = new Media();
        $media->files = $service::upload(self::$file,self::filenameGenerator(),self::$dir);
        $media->type = $type;
        $media->user_id = auth()->id();
        $media->filename = self::$file->getClientOriginalName();
        $media->is_private = self::$isPrivate;
        $media->save();
        return $media;
    }

    public static function thumb(Media $media)
    {
        foreach(config('MediaFile.MediaTypeServices') as $type => $service)
        {
            if($media->type == $type){
                return $service['handler']::thumb($media);
            }
        }
    }

    private static function normalizeExtension($file):string
    {
        return strtolower($file->getClientOriginalExtension());
    }

    private static function filenameGenerator()
    {
        return uniqid();
    }

    public static function getExtensions()
    {
        $extensions = [];
        foreach(config('MediaFile.MediaTypeServices') as $service)
        {
            foreach($service['extensions'] as $extension)
            {
                $extensions [] = $extension;
            }
        }
        return implode(',',$extensions);

    }
}
