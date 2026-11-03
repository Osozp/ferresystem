<?php

use App\Models\Commerce;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;


Route::view('/', 'home')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/expired', function () {
        return view('subscription.expired');
    })->name('subscription.expired');
});

Route::post('/check-email', function (Request $request) {

    $request->validate([
        'email' => 'required|email'
    ]);

    return response()->json([
        'exists' => User::where('email', $request->email)->exists()
    ]);
})->name('check.email');

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get('/auth/google/callback', function () {
    /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
    $driver = Socialite::driver('google');
    $googleUser = $driver->stateless()->user();

    $user = User::where('email', $googleUser->getEmail())->first();

    if ($user) {
        if (!$user->google_id) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'provider'  => 'google',
            ]);
        }
    } else {
        // 2. Si no existe, creamos el Comercio y el Usuario Dueño
        $user = DB::transaction(function () use ($googleUser) {
            $ownerName = $googleUser->getName() ?? 'Usuario Google';
            $commerceName = "Comercio de {$ownerName}";

            // Slug único
            $slugBase = Str::slug($commerceName);
            $slug = $slugBase;
            $count = 1;

            while (Commerce::where('slug', $slug)->exists()) {
                $slug = "{$slugBase}-{$count}";
                $count++;
            }

            // Crear el comercio con 14 días gratis
            $commerce = Commerce::create([
                'name'          => $commerceName,
                'slug'          => $slug,
                'status'        => 'trialing',
                'trial_ends_at' => now()->addDays(7),
            ]);

            // Crear el usuario asignado al comercio
            return User::create([
                'commerce_id'       => $commerce->id,
                'name'              => $ownerName,
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'provider'          => 'google',
                'role'              => 'owner',
                'email_verified_at' => now(),
            ]);
        });
    }

    if (!$user->is_active) {
        return redirect()->route('login')->withErrors([
            'email' => 'Tu cuenta ha sido suspendida temporalmente por el administrador.'
        ]);
    }

    Auth::login($user);
    return redirect()->intended(('/dashboard'));
});

require __DIR__ . '/settings.php';
