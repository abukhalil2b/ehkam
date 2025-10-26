<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required','max:8'],
            'name' => ['required', 'string', 'max:255'],
            'civil_id' => ['required', 'max:10', 'unique:' . User::class],
            'password' => ['required'],
        ]);

        $user = User::create([
            'gender' => $request->gender,
            'user_type' => 'trainee',
            'name' => $request->name,
            'civil_id' => $request->civil_id,
            'plain_password' => $request->password,
            'password' => Hash::make($request->password),
        ]);

        DB::table('user_profile')->insert([
            'user_id' => $user->id,
            'profile_id' => 4,
        ]);

        // event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }
}
