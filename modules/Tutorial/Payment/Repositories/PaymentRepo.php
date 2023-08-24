<?php

namespace Tutorial\Payment\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Tutorial\Payment\Models\Payment;

class PaymentRepo
{
    private $query;
    public function __construct()
    {
        $this->query = Payment::query();
    }

    public function store($data,$discounts = [])
    {
        $payment = Payment::create([
            "buyer_id" => $data['buyer_id'],
            "seller_id" => $data['seller_id'],
            "paymentable_id" => $data['paymentable_id'],
            "paymentable_type" => $data['paymentable_type'],
            "amount" => $data['amount'],
            "invoice_id" => $data['invoice_id'],
            "gateway" => $data['gateway'],
            "status" => $data['status'],
            "seller_p" => $data['seller_p'],
            "seller_share" => $data['seller_share'],
            "site_share" => $data['site_share'],
        ]);
        foreach($discounts as $discount) $discountIds[] = $discount->id;
        if(isset($discountIds))
        return $payment->discounts()->sync($discountIds);

        return $payment;
    }

    public function searchEmail($email)
    {
        if(!is_null($email)){
            $this->query->join('users','users.id','payments.buyer_id')->select('payments.*','users.email')->where('email','LIKE','%'.$email.'%');
        }
        return $this;
    }

    public function searchAmount($amount)
    {
        if(! is_null($amount)){
            $this->query->where('amount',$amount);
        }
        return $this;
    }

    public function searchInvoice($invoice)
    {
        if(! is_null($invoice)){
            $this->query->where('invoice_id','LIKE','%'.$invoice.'%');
        }
        return $this;
    }

    public function searchAfterDate($date)
    {
        if(! is_null($date)){
            $this->query->whereDate('created_at','>=',$date);
        }
        return $this;
    }

    public function searchBeforeDate($date)
    {
        if(! is_null($date)){
            $this->query->whereDate('created_at','<=',$date);
        }
        return $this;
    }

    public function paginate()
    {
        return $this->query->latest()->paginate();
    }

    public function findByInvoiceId($invoiceId)
    {
        return Payment::where('invoice_id', $invoiceId)->first();
    }

    public function changeStatus($id, string $status)
    {
        return Payment::where('id', $id)->update(['status' => $status]);
    }

    public function getLastNDayPayments($day = null, $status)
    {
        $query = Payment::query();
        if (!is_null($day)) $query = $query->where('created_at', '>=', $day);
        return $query->where('status', $status)->latest();
    }

    public function getLastNDaysSuccessPayments($day = null)
    {
        return $this->getLastNDayPayments($day,Payment::STATUS_SUCCESS);
    }

    public function getLastNDayTotal($day = null)
    {
        return $this->getLastNDaysSuccessPayments($day)->sum('amount');
    }

    public function getLastNDaySiteBenefit($day = null)
    {
        return $this->getLastNDaysSuccessPayments($day)->sum('site_share');
    }

    public function getLastNDaySellerShare($day = null)
    {
        return $this->getLastNDaysSuccessPayments($day)->sum('seller_share');
    }

    public function getDayPayments($day,$status)
    {
        return Payment::query()->whereDate('created_at',$day)
        ->where('status', $status)
        ->latest();
    }

    public function getDaySuccessPayments($day)
    {
        return $this->getDayPayments($day,Payment::STATUS_SUCCESS);
    }

    public function getDayFailPayments($day)
    {
        return $this->getDayPayments($day,Payment::STATUS_FAIL);
    }

    public function getDaySuccessPaymentsTotal($day)
    {
        return $this->getDaySuccessPayments($day)->sum('amount');
    }

    public function getDayFailPaymentsTotal($day)
    {
        return $this->getDaySuccessPayments($day)->sum('amount');
    }

    public function getDaySiteShare($day)
    {
        return $this->getDaySuccessPayments($day)->sum('site_share');
    }

    public function getDaySellerShare($day)
    {
        return $this->getDaySuccessPayments($day)->sum('seller_share');
    }

    public function getDailySummery(Collection $dates,$userId = null)
    {
        $query = Payment::query()->where("created_at",">=",$dates->keys()->first())
        ->groupBy('date')
        ->orderBy('date');
        if(! is_null($userId))
        $query->where('seller_id',$userId);

        return $query->get([
            DB::raw("DATE(created_at) as date"),
            DB::raw("SUM(amount) as totalAmount"),
            DB::raw("SUM(site_share) as totalSiteShare"),
            DB::raw("SUM(seller_share) as totalSellerShare"),
        ]);
    }

    public function getSuccessPaymentByUser($userId)
    {
        return Payment::where('seller_id',$userId)->where('status',Payment::STATUS_SUCCESS);
    }

    public function getUserTotalSuccessAmount($userId)
    {
        return $this->getSuccessPaymentByUser($userId)->sum('amount');
    }

    public function getUserTotalBenefit($userId)
    {
        return $this->getSuccessPaymentByUser($userId)->sum('seller_share');
    }

    public function getUserTotalSiteShare($userId)
    {
        return $this->getSuccessPaymentByUser($userId)->sum('site_share');
    }

    public function getUserTotalBenefitByDay($userId,$date)
    {
        return $this->getSuccessPaymentByUser($userId)->whereDate('created_at',$date)->sum('seller_share');
    }

    public function getUserTotalBenefitByPeriod($userId,$startDate,$endDate)
    {
        return $this->getSuccessPaymentByUser($userId)
        ->whereDate('created_at','<=',$startDate)
        ->whereDate('created_at','>=',$endDate)->sum('seller_share');
    }

    public function getUserTotalSellByDay($userId,$date)
    {
        return $this->getSuccessPaymentByUser($userId)
        ->whereDate('created_at',$date)->sum('amount');
    }

    public function getUserSellCountByDay($userId,$date)
    {
        return $this->getSuccessPaymentByUser($userId)
        ->whereDate('created_at',$date)->count();
    }

    public function getPaymentBySellerId($userId)
    {
        return Payment::where('seller_id',$userId);
    }
}
