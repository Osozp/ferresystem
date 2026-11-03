<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CashRegister;
use Symfony\Component\HttpFoundation\Response;

class EnsureCashRegisterIsOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        $hasOpenRegister = CashRegister::where('user_id', Auth::id())
            ->where('status', 'open')
            ->exists();

        if (! $hasOpenRegister) {
            return redirect()->route('admin.cash.index')
                ->with('error', 'Debes abrir una caja antes de realizar ventas o compras.');
        }

        return $next($request);
    }
}
