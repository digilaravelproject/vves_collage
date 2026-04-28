<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * @param Request $request  The incoming HTTP request containing form input and uploaded files.
     * @return RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Step 1: Authenticate the user
        $request->authenticate();

        // Step 2: Regenerate session to prevent session fixation
        $request->session()->regenerate();

        $user = Auth::user();

        // Step 3: Verify if user has any role assigned
        if ($user->roles->isEmpty()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your account is not authorized to access the administrative panel.',
            ]);
        }

        // Step 4: Explicit redirection to the admin dashboard
        // We use redirect()->intended() but provide a solid fallback route
        return redirect()->intended(route('admin.dashboard', [], true));
    }

    /**
     * Handle an incoming authentication request.
     * @param Request $request  The incoming HTTP request containing form input and uploaded files.
     * @return RedirectResponse
     */
    public function store_old(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();

        $request->session()->regenerate();
        // Admin redirect
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard'); // Admin dashboard route
        }


        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
