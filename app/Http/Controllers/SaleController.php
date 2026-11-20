<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);

        $activeRegister = CashRegister::where('user_id', Auth::id())
            ->where('status','open')
            ->first();

        $sales = $activeRegister
            ?Sale::with('products')
            ->where('cash_register_id', $activeRegister->id)
            ->latest()
            ->get()
            : collect();

        return view('admin.sales.index', compact('products', 'sales'));
    }

    public function create(Request $request)
    {
         $search = $request->input('search');

        $products = Product::when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%")
                             ->orWhere('codigo', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->withQueryString(); // ¡Vital para que el paginador recuerde lo que buscaste!

        // Si la petición viene por AJAX desde el buscador o paginador
        if ($request->ajax()) {
            return view('admin.sales.partials.products-table', compact('products'));
        }

        return view('admin.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_date'             => 'required|date',
            'total'                 => 'required|numeric|min:0',
            'items'                 => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $activeRegister = CashRegister::where('user_id', Auth::id())
                    ->where('status','open')
                    ->first();

                // 1. Crear la Venta
                $sale = Sale::create([
                    'user_id'   => Auth::id(),
                    'cash_register_id' => $activeRegister?->id,
                    'total'     => $request->total,
                    'sale_date' => $request->sale_date ?? now(),
                    'status'    => 'completed',
                ]);

                $attachData = [];

                // 2. Procesar cada producto
                foreach ($request->items as $item) {
                    $productId = $item['product_id'];
                    $product = Product::findOrFail($productId);

                    // Validación crítica: Verificar stock suficiente
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuficiente para el producto: {$product->description}. Disponible: {$product->stock}");
                    }

                    // Agrupar si el mismo producto se envió dos veces en el mismo carrito
                    if (isset($attachData[$productId])) {
                        $attachData[$productId]['quantity'] += $item['quantity'];
                        $attachData[$productId]['subtotal'] += ($item['quantity'] * $item['price']);
                    } else {
                        $attachData[$productId] = [
                            'quantity' => $item['quantity'],
                            'price'    => $item['price'],
                            'subtotal' => $item['quantity'] * $item['price'],
                        ];
                    }

                    // Descontar el stock del producto
                    $product->decrement('stock', $item['quantity']);
                }

                $sale->products()->attach($attachData);
            });

            return redirect()->route('admin.sales.index')
                ->with('success', '¡La venta ha sido registrada con éxito!');
        } catch (Exception $e) {
            // Guardar el error detallado en el log de Laravel
            //Log::error('Error al registrar venta: ' , $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de la venta en el modal (JSON)
     */
    public function show(Sale $sale)
    {
        $sale->load('products');

        return response()->json($sale);
    }

    /**
     * Anular venta mediante petición PUT
     */
    public function update(Request $request, Sale $sale)
    {
        return $this->cancelSale($sale);
    }

    /**
     * Anular venta mediante petición DELETE (destroy)
     */
    public function destroy(Sale $sale)
    {
        return $this->cancelSale($sale);
    }

    /**
     * Lógica centralizada para anular la venta y devolver el stock
     */
    private function cancelSale(Sale $sale)
    {
        if ($sale->status === 'canceled') {
            return response()->json([
                'success' => false,
                'message' => 'Esta venta ya fue anulada previamente.'
            ], 400);
        }

        try {
            DB::transaction(function () use ($sale) {
                // 1. Devolver el stock de cada producto vendido (INCREMENT)
                foreach ($sale->products as $product) {
                    Product::where('id', $product->id)
                        ->increment('stock', $product->pivot->quantity);
                }

                // 2. Cambiar el estado de la venta a anulada
                $sale->update([
                    'status' => 'canceled'
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Venta anulada y stock devuelto correctamente.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al intentar anular la venta: ' . $e->getMessage()
            ], 500);
        }
    }
}
