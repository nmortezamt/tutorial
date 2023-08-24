<?php

namespace Tutorial\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Tutorial\User\Http\Requests\ResetPasswordVerifyCodeRquest;
use Tutorial\User\Http\Requests\SendResetPasswordVerifyCodeRequest;
use Tutorial\User\Http\Requests\VerifyCodeRequest;
use Tutorial\User\Models\User;
use Tutorial\User\Repositories\UserRepo;
use Tutorial\User\Services\VerifyCodeService;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function render(): View
    {
        return view('User::auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(SendResetPasswordVerifyCodeRequest $request ,UserRepo $userRepo)
    {
        $user = $userRepo->findByEmail($request->email);
        if(! $user)
        return back()->withErrors(['emailNotFound'=>'کاربری با این ایمیل یافت نشد']);
        VerifyCodeService::delete($user->id);
        if(! VerifyCodeService::has($user->id)){
            $user->sendResetPasswordRequestNotification();
        }
        return view('User::auth.enter-verify-code-form',compact('user'));
    }

    public function checkVerifyCode(ResetPasswordVerifyCodeRquest $request)
    {
        $user = resolve(UserRepo::class)->findByEmail($request->email);
        if($user == null || ! VerifyCodeService::check($user->id,$request->verify_code))
        return back()->withErrors(['verify_code'=>'کد وارد شده نامعتبر است!']);

        auth()->loginUsingId($user->id);
        return redirect()->route('password.show.reset.form');

    }
}
