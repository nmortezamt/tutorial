<?php

namespace Tutorial\RolePermissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Tutorial\Common\Responses\AjaxResponses;
use Tutorial\RolePermissions\Http\Requests\RoleRequest;
use Tutorial\RolePermissions\Http\Requests\RoleUpdateRequest;
use Tutorial\RolePermissions\Models\Role;
use Tutorial\RolePermissions\Repositories\PermissionRepo;
use Tutorial\RolePermissions\Repositories\RoleRepo;

class RolePermissionController extends Controller
{
    private $roleRepo;
    private $permissionRepo;

    public function __construct(RoleRepo $roleRepo,PermissionRepo $permissionRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->permissionRepo = $permissionRepo;
    }

    public function index() :View
    {
        $this->authorize('index',Role::class);
        $roles = $this->roleRepo->all();
        $permissions = $this->permissionRepo->all();
        return view('RolePermission::index',compact('roles','permissions'));
    }

    public function store(RoleRequest $request)
    {
        $this->authorize('create',Role::class);
        $this->roleRepo->store($request);
        return redirect(route('role-permissions.index'));
    }

    public function edit($roleId)
    {
        $this->authorize('edit',Role::class);
        $role = $this->roleRepo->findById($roleId);
        $permissions = $this->permissionRepo->all();
        return view('RolePermission::edit',compact('role','permissions'));
    }

    public function update($roleId,RoleUpdateRequest $request)
    {
        $this->authorize('edit',Role::class);
        $this->roleRepo->update($roleId,$request);
        return redirect(route('role-permissions.index'));
    }

    public function destroy($roleId)
    {
        $this->authorize('delete',Role::class);
        $this->roleRepo->delete($roleId);
        return AjaxResponses::SuccessResponse();
    }
}
