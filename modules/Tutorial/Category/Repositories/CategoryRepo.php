<?php

namespace Tutorial\Category\Repositories;

use Tutorial\Category\Models\Category;

class CategoryRepo{
    public function all()
    {
        return Category::all();
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function allExpectById($id)
    {
        return $this->all()->filter(function($item)use($id){
            return $item->id != $id;
        });
    }

    public function store($data)
    {
        return Category::query()->create([
            'title' => $data->title,
            'slug' => $data->slug,
            'parent_id' => $data->parent_id,
        ]);
    }

    public function update($id,$data)
    {
        return Category::where('id',$id)->update([
            'title' => $data->title,
            'slug' => $data->slug,
            'parent_id' => $data->parent_id
        ]);
    }

    public function delete($id)
    {
        return Category::where('id',$id)->delete();
    }

    public function tree()
    {
        return Category::where('parent_id',null)->with('subcategory')->get();
    }
}
