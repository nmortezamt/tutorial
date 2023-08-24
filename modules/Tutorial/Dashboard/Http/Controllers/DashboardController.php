<?php

namespace Tutorial\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tutorial\Payment\Repositories\PaymentRepo;

class DashboardController extends Controller
{
    public function render(PaymentRepo $paymentRepo)
    {
        $totalSales = $paymentRepo->getUserTotalSuccessAmount(auth()->id());
        $totalSiteShare = $paymentRepo->getUserTotalSiteShare(auth()->id());
        $totalBenefit = $paymentRepo->getUserTotalBenefit(auth()->id());
        $todayBenefit = $paymentRepo->getUserTotalBenefitByDay(auth()->id(), now());
        $last30DaysBenefit = $paymentRepo->getUserTotalBenefitByPeriod(auth()->id(), now(), now()->addDays(-30));
        $todaySuccessPaymentTotal = $paymentRepo->getUserTotalSellByDay(auth()->id(), now());
        $todaySuccessPaymentCount = $paymentRepo->getUserSellCountByDay(auth()->id(), now());

        $payments = $paymentRepo->getPaymentBySellerId(auth()->id())->paginate();
        // chart
        $last30DaysTotal = number_format($paymentRepo->getLastNDayTotal(-30));
        $last30DaysSellerShare = $paymentRepo->getLastNDaySellerShare(-30);
        $totalSell = number_format($paymentRepo->getLastNDayTotal());
        $dates = collect();
        foreach (range(-30, 0) as $i) {
            $dates->put(now()->addDays($i)->format('Y-m-d'), 0);
        }
        $summery = $paymentRepo->getDailySummery($dates,auth()->id());

        return view('Dashboard::index', compact('totalSales', 'totalSiteShare', 'totalBenefit', 'todayBenefit', 'last30DaysBenefit', 'todaySuccessPaymentTotal', 'todaySuccessPaymentCount','dates','summery','totalSell','last30DaysSellerShare','last30DaysTotal','payments'));
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
