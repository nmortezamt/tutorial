<?php

namespace Tutorial\Slider\Repositories;

use Tutorial\Slider\Models\Slide;

class SlideRepo{
    public function all()
    {
        return Slide::query()->orderBy('priority')->get();
    }

    public function findById($id)
    {
        return Slide::findOrFail($id);
    }


    public function store($data)
    {
        return Slide::query()->create([
            'user_id' => auth()->id(),
            'media_id' => $data->media_id,
            'link' => $data->link,
            'priority' => $data->priority,
            'status' => $data->status,
        ]);
    }

    public function update($id,$data)
    {
        return Slide::where('id',$id)->update([
            'media_id' => $data->media_id,
            'link' => $data->link,
            'priority' => $data->priority,
            'status' => $data->status,
        ]);
    }

    public function delete($id)
    {
        return Slide::where('id',$id)->delete();
    }

}
