<?php

namespace Tutorial\Payment\GateWays\Zarinpal;

use Illuminate\Http\Request;
use Tutorial\Payment\Contracts\GateWayContract;
use Tutorial\Payment\Models\Payment;
use Tutorial\Payment\Repositories\PaymentRepo;

class ZarinpalAdaptor implements GateWayContract
{
    private $url;
    private $client;
    public function request($amount, $description)
    {
        $this->client = new zarinpal();
        $callback = route('payments.callback');
        $result = $this->client->request("xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", $amount, $description, "", "", $callback, true);
        if (isset($result["Status"]) && $result["Status"] == 100) {
            $this->url = $result["StartPay"];
            return $result['Authority'];
        } else {
            return [
                "status" => $result["Status"],
                "message" => $result["Message"],
            ];
        }
    }

    public function verify(Payment $payment)
    {
        $this->client = new zarinpal();
        $result = $this->client->verify("xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx", $payment->amount, true);
        if (isset($result["Status"]) && $result["Status"] == 100) {
            return $result["RefID"];
        } else {
            return [
                "status" => $result["Status"],
                "message" => $result["Message"],
            ];
        }
    }

    public function redirect()
    {
        $this->client->redirect($this->url);
    }

    public function getName()
    {
        return "zarinpal";
    }

    public function getInvoiceIdFromRequest(Request $request)
    {
        return $request->Authority;
    }
}
