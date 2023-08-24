<?php

namespace Tutorial\Discount\Repositories;

use Morilog\Jalali\Jalalian;
use Tutorial\Discount\Models\Discount;
use Tutorial\Discount\Services\SettingDate;

class DiscountRepo
{
    public function store($data)
    {
        $discount = Discount::query()->create([
            'user_id' => auth()->id(),
            'code' => $data->code,
            'percent' => $data->percent,
            'usage_limitation' => $data->usage_limitation,
            'expire_at' => $data->expire_at ? Jalalian::fromFormat('Y/m/d H:i', $data->expire_at)->toCarbon() : null,
            'link' => $data->link,
            'type' => $data->type,
            'description' => $data->description,
        ]);
        if ($discount->type == Discount::TYPE_SPECIAL) {
            $discount->courses()->sync($data->courses);
        }
    }

    public function paginate()
    {
        return Discount::query()->latest()->paginate();
    }

    public function delete($id)
    {
        return Discount::query()->where('id', $id)->delete();
    }

    public function findById($id)
    {
        return Discount::query()->findOrFail($id);
    }

    public function update($id, $data)
    {
        Discount::query()->where('id', $id)->update([
            'code' => $data->code,
            'percent' => $data->percent,
            'usage_limitation' => $data->usage_limitation,
            'expire_at' => $data->expire_at ? Jalalian::fromFormat('Y/m/d H:i', $data->expire_at)->toCarbon() : null,
            'link' => $data->link,
            'type' => $data->type,
            'description' => $data->description,
        ]);
        $discount = $this->findById($id);
        if ($discount->type == Discount::TYPE_SPECIAL) {
            $discount->courses()->sync($data->courses);
        } else {
            $discount->courses()->sync([]);
        }
    }

    public function getValidDiscountsQuery($type = Discount::TYPE_ALL, $id = null)
    {
        $query = Discount::query()
            ->where('expire_at', '>', now())
            ->whereNull('code')
            ->where('type', $type);
        if (!is_null($id)) {
            $query->whereHas('courses', function ($query) use ($id) {
                $query->where('id', $id);
            });
        }
        $query->where(function ($query) {
            $query->whereNull('usage_limitation')
                ->orWhere('usage_limitation', '>', '0');
        })
            ->orderBy('percent', 'desc');
        return $query;
    }

    public function getGlobalBiggerDiscount()
    {
        return $this->getValidDiscountsQuery(Discount::TYPE_ALL)
            ->first();
    }

    public function getCourseBiggerDiscount($courseId)
    {
        return $this->getValidDiscountsQuery(Discount::TYPE_SPECIAL, $courseId)
            ->first();
    }

    public function getValidDiscountByCode($courseId, $code)
    {
        return Discount::query()
            ->where('code', $code)
            ->where('expire_at', '>', now())
            ->where(function ($query) {
                $query->whereNull('usage_limitation')
                    ->orWhere('usage_limitation', '>', '0');
            })
            ->where(function ($query) use ($courseId) {
                $query->whereHas('courses', function ($query) use ($courseId) {
                    $query->where('id', $courseId);
                })->orWhereDoesntHave('courses');
            })->first();
    }
}
