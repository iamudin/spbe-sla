<?php
namespace App\Http\Controllers\Plugins\SpbeSla\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Plugins\SpbeSla\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Plugins\SpbeSla\Middleware\RedirectMiddleware;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RedirectMiddleware::handle(), only: ['showLogin', 'login', 'logout']),
        ];
    }
    public function showLogin()
    {
        if (session()->has('spbe_sla_user_id')) {
            return redirect(plugin_route('spbe-sla.public.dashboard'));
        }
        plugin_page_name('Login');
        return view('spbe-sla::public.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Ensure they have a Unit Kerja assigned
            if ($user->unitKerja?->id) {
                session(['spbe_sla_user_id' => $user->id]);
                return redirect(plugin_route('spbe-sla.public.dashboard'));
            } else {
                return back()->withErrors([
                    'email' => 'Your account is not associated with any Unit Kerja.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        session()->forget('spbe_sla_user_id');
        return redirect(plugin_route('spbe-sla.public.login'));
    }
}
