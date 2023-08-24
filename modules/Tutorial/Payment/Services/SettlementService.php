<?php

namespace Tutorial\Payment\Services;

use Tutorial\Payment\Models\Settlement;
use Tutorial\Payment\Repositories\SettlementRepo;

use function Tutorial\Common\newFeedbacks;

class SettlementService
{
    public static function store(object $request)
    {
        $repo = new SettlementRepo();
        $repo->store($request);
        auth()->user()->balance -= $request->amount;
        auth()->user()->save();
        newFeedbacks();
    }

    public static function update(int $settlementId,object $request)
    {
        $repo = new SettlementRepo();
        $settlement = $repo->findById($settlementId);
        $checkSettlement = $repo->getLatestSettlement($settlement->user_id);
        if($checkSettlement->id != $settlementId){
            newFeedbacks('عملیات ناموفق','این درخواست تسویه قابل ویرایش نیست و بایگانی شده است.فقط آخرین درخواست تسویه هر کاربر قابل ویرایش است!','error');
            return redirect(route('settlements.index'));
        }
        

        if(! in_array($settlement->status,[Settlement::STATUS_CANCELLED,Settlement::STATUS_REJECTED]) && in_array($request->status,[Settlement::STATUS_CANCELLED,Settlement::STATUS_REJECTED])){
            $settlement->user->balance += $settlement->amount;
            $settlement->user->save();
        }

        if(in_array($settlement->status,[Settlement::STATUS_CANCELLED,Settlement::STATUS_REJECTED]) && in_array($request->status,[Settlement::STATUS_SETTLED,Settlement::STATUS_PENDING])){
            if($settlement->user->balance < $settlement->amount){
                newFeedbacks('عملیات ناموفق','موجودی حساب کاریر کافی نمیباشد!','error');
                return;
            }
            $settlement->user->balance -= $settlement->amount;
            $settlement->user->save();
        }
        $repo->update($settlement->id,$request);
        newFeedbacks();
    }
}
