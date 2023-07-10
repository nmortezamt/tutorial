<?php

namespace Tutorial\Category\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function getparentAttribute()
    {
        return is_null($this->parent_id) ? 'ندارد' : $this->parentCategory->title;
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function subcategory()
    {
        return $this->hasMany(Category::class,'parent_id');
    }

}
