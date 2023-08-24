<?php

namespace Tutorial\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Payment\Http\Requests\SettlementRequest;
use Tutorial\Payment\Models\Settlement;
use Tutorial\Payment\Repositories\SettlementRepo;
use Tutorial\Payment\Services\SettlementService;
use Tutorial\RolePermissions\Models\Permission;

use function Tutorial\Common\newFeedbacks;

class SettlementController extends Controller
{
    public $settlementRepo;
    public function __construct(SettlementRepo $settlementRepo)
    {
        $this->settlementRepo = $settlementRepo;
    }

    public function index(Request $request)
    {
        $this->authorize('index', Settlement::class);
        if (auth()->user()->hasAnyPermission([Permission::PERMISSION_MANAGE_SETTLEMENTS, Permission::PERMISSION_SUPER_ADMIN])) {
            $settlements = $this->settlementRepo->settled($request->status)->paginate();
        } else {
            $settlements = $this->settlementRepo->settled($request->status)->paginateUserSettlements(auth()->id());
        }
        return view('Payment::settlements.index', compact('settlements'));
    }
    public function create()
    {
        $this->authorize('create', Settlement::class);
        if ($this->settlementRepo->getLatestPendingSettlement(auth()->id())) {
            newFeedbacks('عملیات ناموفق', 'شما یک درخواست تسویه درحال انتظار دارید و نمی توانید درخواست جدیدی فعلا ثبت کنید', 'error');
            return redirect(route('settlements.index'));
        }
        return view('Payment::settlements.create');
    }
    public function store(SettlementRequest $request)
    {
        $this->authorize('create', Settlement::class);
        if ($this->settlementRepo->getLatestPendingSettlement(auth()->id())) {
            newFeedbacks('عملیات ناموفق', 'شما یک درخواست تسویه درحال انتظار دارید و نمی توانید درخواست جدیدی فعلا ثبت کنید', 'error');
            return redirect(route('settlements.index'));
        }
        SettlementService::store($request);
        return redirect(route('settlements.index'));
    }

    public function edit($settlementId)
    {
        $this->authorize('manage', Settlement::class);
        $requestedSettlement = $this->settlementRepo->findById($settlementId);
        $settlement = $this->settlementRepo->getLatestSettlement($requestedSettlement->user_id);
        if ($settlement->id != $settlementId) {
            newFeedbacks('عملیات ناموفق', 'این درخواست تسویه قابل ویرایش نیست و بایگانی شده است.فقط آخرین درخواست تسویه هر کاربر قابل ویرایش است!', 'error');
            return redirect(route('settlements.index'));
        }
        return view('Payment::settlements.edit', compact('settlement'));
    }

    public function update($settlement, SettlementRequest $request)
    {
        $this->authorize('manage', Settlement::class);
        SettlementService::update($settlement, $request);
        return redirect(route('settlements.index'));
    }
}
