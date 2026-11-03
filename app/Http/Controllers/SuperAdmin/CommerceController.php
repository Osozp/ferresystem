<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Commerce;
use App\Models\CommercePayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommerceController extends Controller
{
    public function index(Request $request)
    {
        $totalCommerces = Commerce::count();
        $activeCommerces = Commerce::where('status', 'active')->count();
        $trialCommerces = Commerce::where('status', 'trialing')->count();
        $suspendedCommerces = Commerce::where('status', 'suspended')->count();
        $totalUsers = User::count();
        $totalRevenue = CommercePayment::sum('amount');

        $query = Commerce::withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $commerces = $query->latest()->paginate(15);

        return view('superadmin.commerces.index', compact(
            'commerces',
            'totalCommerces',
            'activeCommerces',
            'trialCommerces',
            'suspendedCommerces',
            'totalUsers',
            'totalRevenue'
        ));
    }

    public function updateStatus(Request $request, Commerce $commerce)
    {
        $request->validate([
            'status' => 'required|in:active,trialing,suspended,canceled',
            'trial_ends_at' => 'nullable|date',
            'subscription_ends_at' => 'nullable|date',
        ]);

        $commerce->update([
            'status' => $request->status,
            'trial_ends_at' => $request->trial_ends_at,
            'subscription_ends_at' => $request->subscription_ends_at,
        ]);

        return redirect()->back()->with('success', "Estado del comercio '{$commerce->name}' actualizado correctamente.");
    }

    /**
     * Permite crear un comercio manualmente desde el panel de Super Admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,trialing,suspended',
            'days' => 'nullable|integer|min:1',
        ]);

        $slugBase = \Illuminate\Support\Str::slug($request->name);
        $slug = $slugBase;
        $count = 1;

        while (Commerce::where('slug', $slug)->exists()) {
            $slug = "{$slugBase}-{$count}";
            $count++;
        }

        $commerce = Commerce::create([
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->status,
            'trial_ends_at' => $request->status === 'trialing' ? now()->addDays($request->get('days', 14)) : null,
            'subscription_ends_at' => $request->status === 'active' ? now()->addDays($request->get('days', 30)) : null,
        ]);

        return redirect()->back()->with('success', "Comercio '{$commerce->name}' creado con éxito.");
    }

    public function extendSubscription(Request $request, Commerce $commerce)
    {
        $request->validate([
            'days' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Calcular la nueva fecha de expiración
        $currentExpiration = $commerce->subscription_ends_at && $commerce->subscription_ends_at->isFuture()
            ? $commerce->subscription_ends_at
            : now();

        $newExpiration = $currentExpiration->addDays((int) $request->days);

        // Actualizar el comercio: pasa a 'active' y se extiende la fecha
        $commerce->update([
            'status' => 'active',
            'subscription_ends_at' => $newExpiration,
        ]);

        // Registrar el pago en el historial
        CommercePayment::create([
            'commerce_id' => $commerce->id,
            'amount' => $request->amount,
            'days_added' => $request->days,
            'reference' => $request->reference,
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', "Suscripción extendida por {$request->days} días para '{$commerce->name}'.");
    }

    public function impersonate(Commerce $commerce)
    {
        // Buscamos al usuario dueño ('owner') del comercio
        $owner = $commerce->users()->where('role', 'owner')->first() ?? $commerce->users()->first();

        if (!$owner) {
            return redirect()->back()->with('error', 'Este comercio no tiene usuarios registrados.');
        }

        // Guardamos el ID del admin original en la sesión para poder regresar
        session()->put('impersonated_by', Auth::id());

        // Autenticamos al usuario del comercio
        Auth::login($owner);

        return redirect()->route('dashboard')->with('success', "Has accedido como comercio: {$commerce->name}");
    }

    /**
     * Regresa a la cuenta de Super Admin.
     */
    public function leaveImpersonation()
    {
        $adminId = session()->pull('impersonated_by');

        if ($adminId) {
            Auth::loginUsingId($adminId);
            return redirect()->route('admin.superadmin.commerces.index')->with('success', 'Has regresado a tu cuenta de Super Admin.');
        }

        return redirect()->route('dashboard');
    }
}
