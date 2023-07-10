<?php

namespace Tutorial\RolePermissions\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepo
{
    public function all()
    {
        return Role::all();
    }

    public function store($data)
    {
        return Role::create(['name'=>$data->name])->syncPermissions($data->permissions);
    }

    public function findById($id)
    {
        return Role::findOrFail($id);
    }

    public function update($id,$data)
    {
        $role = $this->findById($id);
        return $role->syncPermissions($data->permissions)->update(['name'=>$data->name]);
    }

    public function delete($id){
        return Role::where('id',$id)->delete();
    }
}
