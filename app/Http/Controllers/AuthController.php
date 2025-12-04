<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {

         if (Auth::check()) {
		       //Redirect ke halaman dashboard
		    }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {

            Auth::login($user);
            session(['last_login' => now()]);

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        } else {
            return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
        }
    }
    function logout(Request $request)
{
		Auth::logout();
    $request->session()->invalidate();     // Hapus semua session
    $request->session()->regenerateToken(); // Cegah CSRF

    return redirect()->route('auth');
		// Redirect ke halaman login
}
}
