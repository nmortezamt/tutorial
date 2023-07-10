<?php

namespace Tutorial\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Tutorial\User\Http\Requests\ChangePasswordRquest;
use Tutorial\User\Services\UserService;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function render(Request $request): View
    {
        return view('User::auth.reset-password');
    }


    public function store(ChangePasswordRquest $request): RedirectResponse
    {

        UserService::changePassword(auth()->user(),$request->password);

        return redirect()->to(RouteServiceProvider::HOME);
    }
}
