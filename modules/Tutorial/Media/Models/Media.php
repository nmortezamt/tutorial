<?php

namespace Tutorial\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tutorial\Media\Services\MediaFileService;

class Media extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = ['files' => 'json'];

    public function getThumbAttribute()
    {
        return MediaFileService::thumb($this);
    }

    protected static function booted()
    {
        static::deleting(function ($media) {
            MediaFileService::delete($media);
        });
    }

    public function getUrl($original = "original")
    {
        return "/storage/" . $this->files[$original];
    }
}
