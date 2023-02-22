<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use function redirect;

class SignUpController extends Controller {

    public function create() {
        
    }

    public function store(Request $request) {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:64',
                'regex:/^[a-z]([a-z0-9]|[a-z0-9]\.[a-z0-9])*$/i',
                'unique:' . User::class
            ],
            'password' => ['required', 'string', 'confirmed', Password::min(6)]
        ]);

        $user = User::create([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

}
