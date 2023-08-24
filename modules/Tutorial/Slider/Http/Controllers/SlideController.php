<?php

namespace Tutorial\Slider\Http\Controllers;

use App\Http\Controllers\Controller;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\Media\Services\MediaFileService;
use Tutorial\Slider\Http\Requests\SlideRequest;
use Tutorial\Slider\Models\Slide;
use Tutorial\Slider\Repositories\SlideRepo;

use function Tutorial\Common\newFeedbacks;

class SlideController extends Controller
{
    public $SlideRepo;

    public function __construct(SlideRepo $slideRepo)
    {
        $this->SlideRepo = $slideRepo;
    }

    public function index()
    {
        $this->authorize('manage',Slide::class);
        $slides = $this->SlideRepo->all();
        return view('Slider::index',compact('slides'));
    }

    public function store(SlideRequest $request)
    {
        $this->authorize('manage',Slide::class);
        $request->request->add(['media_id'=>MediaFileService::publicUpload($request->file('image'))->id]);
        $this->SlideRepo->store($request);
        return redirect()->route('slider.index');
    }

    public function edit($id)
    {
        $this->authorize('manage',Slide::class);
        $slide = $this->SlideRepo->findById($id);
        return view('Slider::edit',compact('slide'));
    }

    public function update($id,SlideRequest $request)
    {
        $this->authorize('manage',Slide::class);
        $slide = $this->SlideRepo->findById($id);
        if($request->hasFile('image')){
            $request->request->add(['media_id'=>MediaFileService::publicUpload($request->file('image'))->id]);
            if($slide->media)
                $slide->media->delete();
        }else{
            $request->request->add(['media_id'=>$slide->media_id]);
        }
        $this->SlideRepo->update($id,$request);
        newFeedbacks();
        return redirect()->route('slider.index');
    }

    public function destroy($id)
    {
        $this->authorize('manage',Slide::class);
        $slide = $this->SlideRepo->findById($id);
        if($slide->media){
            $slide->media->delete();
        }
        $this->SlideRepo->delete($id);
        return AjaxResponses::SuccessResponse();
    }
}
