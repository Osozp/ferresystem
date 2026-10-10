<?php
namespace App\Http\Controllers;

use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashRegisterController extends Controller
{
    // Mostrar formulario de apertura (si no hay caja abierta) o panel de caja actual
    public function index()
    {
        $user = Auth::user();
        // Asumiendo que el usuario tiene una relación o campo commerce_id
        $commerceId = $user->commerce_id ?? session('commerce_id');

        $activeRegister = CashRegister::where('commerce_id', $commerceId)
            ->where('status', 'open')
            ->first();

        return view('admin.cash.index', compact('activeRegister'));
    }

    // Abrir Caja
    public function store(Request $request)
    {
        $request->validate([
            'opening_balance' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $commerceId = $user->commerce_id ?? session('commerce_id');

        // Verificar que el comercio no tenga ya una caja abierta
        $openRegister = CashRegister::where('commerce_id', $commerceId)
            ->where('status', 'open')
            ->first();

        if ($openRegister) {
            return back()->with('error', 'Ya existe una caja abierta para este comercio.');
        }

        CashRegister::create([
            'commerce_id'     => $commerceId,
            'user_id'         => Auth::id(),
            'opening_balance' => $request->opening_balance,
            'status'          => 'open',
            'opened_at'       => now(),
        ]);

        return redirect()->route('admin.sales.create')->with('success', '¡Caja abierta exitosamente!');
    }

    // Procesar Cierre / Arqueo de Caja
    public function close(Request $request, CashRegister $cashRegister)
    {
        $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'notes'           => 'nullable|string|max:500',
        ]);

        if ($cashRegister->status === 'closed') {
            return back()->with('error', 'Esta caja ya se encuentra cerrada.');
        }

        // Calcular totales del turno
        $totalSales = $cashRegister->sales()->sum('total');
        $totalPurchases = $cashRegister->purchases()->sum('total'); // Sumar el total de compras del turno

        // Monto esperado en caja = Inicio + Ventas - Compras
        $expectedBalance = $cashRegister->opening_balance + $totalSales - $totalPurchases;

        // Diferencia = Lo que contó físicamente - Lo que el sistema esperaba
        $closingBalance = $request->closing_balance;
        $difference = $closingBalance - $expectedBalance;

        $cashRegister->update([
            'closing_balance'  => $closingBalance,
            'expected_balance' => $expectedBalance,
            'difference'       => $difference,
            'status'           => 'closed',
            'notes'            => $request->notes,
            'closed_at'        => now(),
        ]);

        return redirect()->route('admin.cash.index')->with('success', 'Caja cerrada correctamente. Diferencia registrada: ' . number_format($difference, 2) . ' Bs.');
    }
}
