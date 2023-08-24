<?php

namespace Tutorial\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\User\Http\Requests\UpdateUserRequest;
use Tutorial\Media\Services\MediaFileService;
use Tutorial\RolePermissions\Repositories\RoleRepo;
use Tutorial\User\Http\Requests\AddRoleRequest;
use Tutorial\User\Http\Requests\UpdateProfileInformationRequest;
use Tutorial\User\Http\Requests\UpdateUserPhotoRequest;
use Tutorial\User\Models\User;
use Tutorial\User\Repositories\UserRepo;

use function Tutorial\Common\newFeedbacks;

class UserController extends Controller
{
    public $userRepo;
    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(RoleRepo $roleRepo)
    {
        $this->authorize('index',User::class);
        $roles = $roleRepo->all();
        $users = $this->userRepo->paginate();
        return view('User::admin.index',compact('users','roles'));
    }

    public function addRole(AddRoleRequest $request,User $user)
    {
        $this->authorize('addRole',User::class);
        newFeedbacks('عملیات موفق',"به کاربر {$user->name} نقش کاربری {$request->role} داده شد",'success');
        $user->assignRole($request->role);
        return back();
    }

    public function removeRole($user,$role)
    {
        $this->authorize('removeRole',User::class);
        $user = $this->userRepo->findById($user);
        $user->removeRole($role);
        return AjaxResponses::SuccessResponse();
    }

    public function manualVerify($userId)
    {
        $this->authorize('manualVerify',User::class);
        $user = $this->userRepo->findById($userId);
        $user->markEmailAsVerified();
        return AjaxResponses::SuccessResponse();
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }

    public function info($user)
    {
        $this->authorize('index',User::class);
        $user = $this->userRepo->findByIdFullInfo($user);
        return view('User::admin.info',compact('user'));
    }

    public function edit($userId)
    {
        $this->authorize('edit', User::class);
        $user = $this->userRepo->findById($userId);
        return view('User::admin.edit',compact('user'));
    }

    public function update(UpdateUserRequest $request, $userId)
    {
        $this->authorize('edit', User::class);
        $user = $this->userRepo->findById($userId);
        if ($request->hasFile('image')) {
            $request->request->add(['image_id' => MediaFileService::publicUpload($request->file('image'))->id]);
            if ($user->image_id)
                $user->image->delete();
        } else {
            $request->request->add(['image_id' => $user->image_id]);
        }
        $this->userRepo->update($userId,$request);
        newFeedbacks();
        return redirect(route('users.index'));
    }

    public function updatePhoto(UpdateUserPhotoRequest $request)
    {
        $this->authorize('editProfile', User::class);
        $media = MediaFileService::publicUpload($request->user_photo);
        if(auth()->user()->image) auth()->user()->image->delete();
        auth()->user()->image_id = $media->id;
        auth()->user()->save();
        newFeedbacks();
        return bacK();
    }

    public function profile()
    {
        $this->authorize('editProfile', User::class);
        $user = $this->userRepo->findById(auth()->id());
        return view('User::admin.profile',compact('user'));
    }

    public function updateProfile(UpdateProfileInformationRequest $request)
    {
        $this->authorize('editProfile', User::class);
        $this->userRepo->updateProfile($request);
        newFeedbacks();
        return back();
    }


    public function destroy($userId)
    {
        $this->authorize('delete',User::class);
        $this->userRepo->delete($userId);
        return AjaxResponses::SuccessResponse();
    }

}
