<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Jenssegers\Agent\Agent;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        if (Auth::check()) {
            return to_route('myaccount.overview');
            //return redirect(RouteServiceProvider::HOME);
        }
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'phone' => 'required|string',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
        $agent = new Agent();
        Activity::create([
            'actions' => 'Login',
            'user_id' => Auth::id(),
            'value' => [
                'platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'device' => $agent->device(),
                'ip' => $request->ip(),
                'desktop' => $agent->isDesktop(),
            ],
        ]);

        return to_route('myaccount.overview');
    }
}
