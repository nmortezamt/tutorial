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
        return '/storage/' . $this->files[300];
    }

    protected static function booted()
    {
        static::deleting(function ($media) {
            MediaFileService::delete($media);
        });
    }
}
