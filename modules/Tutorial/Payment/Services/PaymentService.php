<?php

namespace Tutorial\Payment\Services;

use Tutorial\Payment\GateWays\GateWay;
use Tutorial\Payment\Models\Payment;
use Tutorial\Payment\Repositories\PaymentRepo;
use Tutorial\User\Models\User;

use function Tutorial\Common\newFeedbacks;

class PaymentService
{
    public static function generate($amount,$paymentable,User $buyer,$seller = null,$discounts = [])
    {
        if($amount<=0 || is_null($paymentable->id) || is_null($buyer->id)) return false;

        $gateway = resolve(GateWay::class);
        $invoice_id = $gateway->request($amount,$paymentable->title);

        if(is_array($invoice_id)){
            newFeedbacks("عملیات ناموفق", $invoice_id["message"], "error");
            return back();
        }

        if(! is_null($paymentable->teacher_percent)){
            $seller_p = $paymentable->teacher_percent;
            $seller_share = ($amount / 100) * $seller_p;
            $site_share = $amount - $seller_share;
        }else{
            $seller_p = $seller_share = 0;
            $site_share = $amount;
        }

        return resolve(PaymentRepo::class)->store([
            "buyer_id" => $buyer->id,
            "seller_id" => $seller,
            "paymentable_id" => $paymentable->id,
            "paymentable_type" => get_class($paymentable),
            "amount" => $amount,
            "invoice_id" => $invoice_id,
            "gateway" => $gateway->getName(),
            "status" => Payment::STATUS_PENDING,
            "seller_p" => $seller_p,
            "seller_share" => $seller_share,
            "site_share" => $site_share,
        ],$discounts);
    }
}
