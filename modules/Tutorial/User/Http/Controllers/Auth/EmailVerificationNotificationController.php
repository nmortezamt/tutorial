<?php

namespace Tutorial\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Tutorial\User\Models\User;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function verifySend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'کد فعال سازی مجدد با موفقیت ارسال شد');
    }

    public function passwordSend(User $user): RedirectResponse
    {
        $user->sendResetPasswordRequestNotification();

        return back()->with('status', 'کد بازیابی رمز عبور مجدد با موفقیت ارسال شد');
    }
}
