<?php

namespace Tutorial\Payment\Repositories;

use Tutorial\Payment\Models\Settlement;

class SettlementRepo
{
    private $query;
    public function __construct()
    {
        $this->query = Settlement::query();
    }

    public function store($data)
    {

        return Settlement::query()->create([
            'user_id' => auth()->id(),
            'to' => [
                'name' => $data->name,
                'card' => $data->card
            ],
            'amount' => $data->amount
        ]);
    }

    public function settled($status)
    {
        if (!is_null($status)) {
            $this->query->where('status', $status);
        }
        return $this;
    }

    public function paginate()
    {
        return $this->query->latest()->paginate();
    }

    public function findById($id)
    {
        return Settlement::query()->findOrFail($id);
    }

    public function update(int $id,object $data)
    {
        return Settlement::query()->where('id',$id)->update([
            'from'=>[
                'name'=>$data->from['name'],
                'card'=>$data->from['card'],
            ],
            'to'=>[
                'name'=>$data->to['name'],
                'card'=>$data->to['card'],
            ],
            'status'=>$data->status,
            'amount'=>$data->amount
        ]);
    }

    public function getLatestPendingSettlement(int $userId)
    {
        return Settlement::query()->where('user_id',$userId)
        ->where('status',Settlement::STATUS_PENDING)
        ->latest()
        ->first();
    }

    public function getLatestSettlement(int $userId)
    {
        return Settlement::query()->where('user_id',$userId)
        ->latest()
        ->first();
    }

    public function paginateUserSettlements($userId)
    {
        return Settlement::query()->where('user_id',$userId)
        ->latest()
        ->paginate();
    }
}
