<?php

namespace Tutorial\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Tutorial\Payment\Events\PaymentWasSuccessful;
use Tutorial\Payment\GateWays\GateWay;
use Tutorial\Payment\Models\Payment;
use Tutorial\Payment\Repositories\PaymentRepo;

use function Tutorial\Common\dateFromJalali;
use function Tutorial\Common\newFeedbacks;

class PaymentController extends Controller
{
    public $paymentRepo;
    public function __construct(PaymentRepo $paymentRepo)
    {
        $this->paymentRepo = $paymentRepo;
    }

    public function index(Request $request)
    {
        $this->authorize('manage', Payment::class);
        $payments = $this->paymentRepo->searchEmail($request->email)
            ->searchAmount($request->amount)
            ->searchInvoice($request->invoice_id)
            ->searchAfterDate(dateFromJalali($request->start_date))
            ->searchBeforeDate(dateFromJalali($request->end_date))->paginate();
        $last30DaysTotal = number_format($this->paymentRepo->getLastNDayTotal(-30));
        $last30DaysBenefit = $this->paymentRepo->getLastNDaySiteBenefit(-30);
        $last30DaysSellerShare = $this->paymentRepo->getLastNDaySellerShare(-30);
        $totalSell = number_format($this->paymentRepo->getLastNDayTotal());
        $totalBenefit = number_format($this->paymentRepo->getLastNDaySiteBenefit());
        $dates = collect();
        foreach (range(-30, 0) as $i) {
            $dates->put(now()->addDays($i)->format('Y-m-d'), 0);
        }
        $summery = $this->paymentRepo->getDailySummery($dates);
        return view('Payment::index', compact(
            'payments',
            'last30DaysTotal',
            'last30DaysBenefit',
            'totalSell',
            'totalBenefit',
            'dates',
            'summery',
            'last30DaysSellerShare'
        ));
    }

    public function callback(Request $request)
    {
        $gateway = resolve(GateWay::class);
        $paymentRepo = new PaymentRepo();
        $payment = $paymentRepo->findByInvoiceId($gateway->getInvoiceIdFromRequest($request));
        if (!$payment) {
            newFeedbacks("تراکنش ناموفق", "تراکنش مورد نظر یافت نشد", "error");
            return redirect('/');
        }
        $result = $gateway->verify($payment);
        if (is_array($result)) {
            newFeedbacks("عملیات ناموفق", $result["message"], "error");
            $paymentRepo->changeStatus($payment->id, Payment::STATUS_FAIL);
        } else {
            event(new PaymentWasSuccessful($payment));
            newFeedbacks("عملیات ناموفق", "پرداخت با موفقیت انجام شد", "success");
            $paymentRepo->changeStatus($payment->id, Payment::STATUS_SUCCESS);
        }
        return redirect()->to($payment->paymentable->path());
    }

    public function purchases()
    {
        $payments = auth()->user()->payments()->with('paymentable')->paginate();
        return view('Payment::purchases',compact('payments'));
    }
}
