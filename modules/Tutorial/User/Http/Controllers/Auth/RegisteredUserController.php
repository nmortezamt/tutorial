<?php

namespace Tutorial\User\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Tutorial\User\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Tutorial\User\Rules\ValidMobile as RulesValidMobile;
use Tutorial\User\Rules\ValidPassword as RulesValidPassword;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function render(): View
    {
        return view('User::auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'mobile' => ['nullable', 'string',new RulesValidMobile,'unique:'.User::class],
            'password' => ['required',new RulesValidPassword,'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
