<?php

namespace Tutorial\User\Repositories;

use Tutorial\RolePermissions\Models\Permission;
use Tutorial\User\Models\User;

class UserRepo
{
    public function findByEmail(string $email)
    {
        return User::query()->where('email',$email)->first();
    }

    public function getTeachers(){
        return User::permission(Permission::PERMISSION_MANAGE_TEACH)->get();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function findByIdFullInfo($id)
    {
        return User::query()->where('id',$id)->with(['purchases','courses','settlements','payments'])->first();
    }

    public function paginate()
    {
        return User::latest()->paginate();
    }

    public function update($id,$data)
    {
        $update = [
            'name'=>$data->name,
            'email'=>$data->email,
            'username'=>$data->username,
            'mobile'=>$data->mobile,
            'headLine'=>$data->headLine,
            'telegram'=>$data->telegram,
            'status'=>$data->status,
            'image_id'=>$data->image_id,
            'bio'=>$data->bio,
        ];

        if(! is_null($data->password)){
            $update['password'] = bcrypt($data->password);
        }
        return User::where('id',$id)->update($update);
    }

    public function updateProfile($data)
    {
        auth()->user()->name = $data->name;
        auth()->user()->telegram = $data->telegram;
        auth()->user()->mobile = $data->mobile;
        if(auth()->user()->email != $data->email){
        auth()->user()->email = $data->email;
        auth()->user()->email_verified_at = null;
        }

        if(auth()->user()->hasPermissionTo(Permission::PERMISSION_MANAGE_TEACH)){
            auth()->user()->card_number = $data->card_number;
            auth()->user()->shaba = $data->shaba;
            auth()->user()->headLine = $data->headLine;
            auth()->user()->username = $data->username;
            auth()->user()->bio = $data->bio;
        }

        if(! is_null($data->password)){
            auth()->user()->password = bcrypt($data->password);
        }
        auth()->user()->save();
    }

    public function delete($id)
    {
        return User::where('id',$id)->delete();
    }
}
