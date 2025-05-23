<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Custom role-based redirect
            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects',
        ]);
    }

    protected function redirectToDashboard(): RedirectResponse
    {
        $user = Auth::user();
        
        return match($user->role) {
            'Admin' => redirect()->route('Admin.dashboard'),
            'Manager' => redirect()->route('Manager.dashboard'),
            'Employé' => redirect()->route('Employé.dashboard'),
            default => redirect('/')
        };
    }
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}