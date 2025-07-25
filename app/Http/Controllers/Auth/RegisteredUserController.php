<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Nnjeim\World\Models\Country;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $countries = Country::orderBy('name')
                            ->get(['id','name']);
        return view('auth.register', compact('countries'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'alias'     => [
                'required',
                'string',
                'max:30',
                'unique:users,alias',
                'regex:/^[A-Za-z0-9_]+$/'  // solo A–Z, a–z, 0–9 y _
            ],
            'email'     => ['required','email:rfc,dns','max:150','unique:users,email'],
            'country_id' => ['required', 'integer','exists:countries,id'],
            'state_id'   => ['required', 'integer','exists:states,id'],
            'city_id'    => ['required', 'integer','exists:cities,id'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'      => $request->input('name'),
            'alias'     => $request->input('alias'),
            'email'     => $request->input('email'),
            'city_id'   => $request->input('city_id'),
            'password'  => Hash::make($request->input('password')),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::homeFor($user));
    }
}
