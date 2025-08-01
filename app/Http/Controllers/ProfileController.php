<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\ExchangeRequest;
use Illuminate\Support\Facades\Hash;
use Nnjeim\World\Models\Country;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        // Completados
        $completados = ExchangeRequest::where(function ($q) use ($user) {
            $q->where('requester_id', $user->id)
            ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id));
        })->where('status', 'completed')->count();

        // Cancelados post-match (con chat asociado)
        $cancelados = ExchangeRequest::where('status', 'cancelled')
            ->where(function ($q) {
                $q->where('cancelled_by_requester', true)
                ->orWhere('cancelled_by_owner', true);
            })
            ->whereHas('chat') // 👈 Esto asegura que hubo match antes de la cancelación
            ->where(function ($q) use ($user) {
                $q->where('requester_id', $user->id)
                ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id))
                ->orWhereHas('requestedItem', fn($q) => $q->where('user_id', $user->id));
            })
            ->count();

        $total = $completados + $cancelados;
        $tasa = $total > 0 ? round(($completados / $total) * 100) : 0;

        return view('profile.show', compact('user', 'completados', 'cancelados', 'tasa', 'total'));
    }


    /**
     * Display the user's profile form.
     */ 
    public function edit(Request $request): View
    {
        $user = $request->user();

        $puedeCambiarUbicacion = !ExchangeRequest::where(function ($q) use ($user) {
            $q->where('requester_id', $user->id)
            ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id))
            ->orWhereHas('requestedItem', fn($q) => $q->where('user_id', $user->id));
        })->whereIn('status', ['pending', 'accepted'])->exists();

        $countries = Country::orderBy('name')->get(['id', 'name']);

        return view('profile.edit', [
            'user' => $user,
            'puedeCambiarUbicacion' => $puedeCambiarUbicacion,
            'countries' => $countries,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Comprobar si tiene intercambios en curso o pendientes
        $bloqueado = ExchangeRequest::where(function ($q) use ($user) {
            $q->where('requester_id', $user->id)
                ->orWhereHas('offeredItem', fn($q) => $q->where('user_id', $user->id))
                ->orWhereHas('requestedItem', fn($q) => $q->where('user_id', $user->id));
        })->whereIn('status', ['pending', 'accepted'])->exists();

        // Validación dinámica
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ];

        if (!$bloqueado) {
            $rules['city_id'] = ['nullable', 'integer', 'exists:cities,id'];
        }

        $data = $request->validate($rules);

        // Si no puede cambiar city_id, asegurate que no venga manipulada
        if ($bloqueado) {
            unset($data['city_id']);
        }

        $user->update($data);

        return redirect()->route('profile.show', $user)->with('success', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function editPassword()
    {
        return view('profile.password');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show', $user)->with('success', 'Contraseña actualizada correctamente.');
    }
}
