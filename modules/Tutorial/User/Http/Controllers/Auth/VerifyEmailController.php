<?php

namespace Tutorial\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Tutorial\User\Http\Requests\VerifyCodeRequest;
use Tutorial\User\Services\VerifyCodeService;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function verify(VerifyCodeRequest $request)
    {
        if(! VerifyCodeService::check(auth()->id(),$request->verify_code))
        return back()->withErrors(['verify_code'=>'کد وارد شده نامعتبر است!']);

        auth()->user()->markEmailAsVerified();
        return Redirect::to(RouteServiceProvider::HOME);
    }
}
