<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'text_username' => 'required|min:3',
            'text_password' => 'required|min:6',
        ], [
            'text_username.required' => 'O campo e-mail é obrigatório.',
            'text_username.email' => 'O campo de e-mail deve conter um endereço válido.',
            'text_username.min' => 'O campo e-mail deve ter no mínimo 3 caracteres.',
            'text_password.required' => 'O campo password é obrigatório.',
            'text_password.min' => 'O campo password deve ter no mínimo 6 caracteres.',
        ]);

        $credentials = [
            'username' => $request->input('text_username'),
            'password' => $request->input('text_password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Auth::user()->update(['last_login' => now()]);

            return redirect()->intended('/');
        }

        return back()
            ->withInput()
            ->with('login_error', 'Username ou password incorretos!');
    }

    public function logout(Request $request)
    {
        // Desloga o usuário e limpa a sessão padrão do Laravel
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
