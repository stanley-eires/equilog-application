<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Activity;
use App\Models\SiteOptions;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Jenssegers\Agent\Agent;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {

        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        if (Auth::user()->status == 0) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->with(
                'message',
                [
                    'title' => "Account Blocked",
                    'content' => "You account has been temporarily block by the site administrator. Contact support for help",
                    'status' => 'danger'
                ]
            );
        }

        User::where('id', $request->user()->id)->update(['login_at' => date('r')]);
        $request->session()->regenerate();
        session()->flash('message', [
            'title' => "Logged In",
            'content' => 'Welcome <strong>' . Auth::user()->name . '</strong>',
            'status' => null
        ]);
        $agent = new Agent();
        Activity::create([
            'actions' => 'login',
            'user_id' => Auth::id(),
            'value' => [
                'platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'device' => $agent->device(),
                'ip' => $request->ip(),
                'desktop' => $agent->isDesktop(),
            ],
        ]);

        return redirect()->intended(route('myaccount.overview'));
    }
    public function socialLogin(Request $request)
    {
        $user = User::select('id', 'name')->where(['email' => $request->email])->first();
        if ($user) {
            User::where('id', $user->id)->update(['login_at' => date('r'), 'email_verified_at' => date('Y-m-d H:i:s')]);
        } else {
            $user = User::create([
                'name' => $request->name, 'email' => $request->email,
                'password' => Hash::make($request->email), 'email_verified_at' => date('Y-m-d H:i:s')
            ]);
        }
        Auth::loginUsingId($user->id);
        $request->session()->regenerate();
        session()->flash('message', [
            'title' => "Logged In",
            'content' => 'Welcome <strong>' . Auth::user()->name . '</strong>',
            'status' => null
        ]);
        $agent = new Agent();
        Activity::create([
            'actions' => 'login',
            'user_id' => Auth::id(),
            'value' => [
                'platform' => $agent->platform() . ' ' . $agent->version($agent->platform()),
                'browser' => $agent->browser(),
                'device' => $agent->device(),
                'ip' => $request->ip(),
                'desktop' => $agent->isDesktop(),
            ],
        ]);
        return redirect()->intended(route('myaccount.overview'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('message', [
            'title' => "Logged Out",
            'content' => 'You are logged out successfully from this device',
            'status' => null
        ]);

        return to_route('public.home');
    }
}
