<?php

namespace Tutorial\Payment\Contracts;

use Illuminate\Http\Request;
use Tutorial\Payment\Models\Payment;

interface GateWayContract
{
    public function request($amount,$description);

    public function verify(Payment $payment);

    public function redirect();

    public function getName();

}

