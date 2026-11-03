<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listado de productos para la sección superior/derecha
        $products = Product::paginate(10);

        // Listado de compras realizadas para la sección inferior
        $purchases = Purchase::with('products')->latest()->paginate(10);

        return view('admin.purchases.index', compact('products', 'purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('admin.purchases.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validaciones
        $request->validate([
            'purchase_date'         => 'required|date',
            'total'                 => 'required|numeric|min:0',
            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.price'         => 'required|numeric|min:0',
            'items.*.selling_price' => 'required|numeric|min:0',
        ]);

        try {
            // 2. Transacción
            DB::transaction(function () use ($request) {

            $activeRegister = CashRegister::where('user_id', Auth::id())
                ->where('status','open')
                ->first();

                // Paso A: Crear cabecera
                $purchase = Purchase::create([
                    'purchase_date' => $request->purchase_date,
                    'cash_register_id' => $activeRegister?->id,
                    'total'         => $request->total,
                    'status'        => 'completed', // Estado inicial sugerido
                ]);

                // Paso B: Agrupar o formatear ítems para evitar duplicados en el array de attach
                $attachData = [];
                foreach ($request->items as $item) {
                    $productId = $item['product_id'];

                    if (isset($attachData[$productId])) {
                        // Si se envió el mismo producto 2 veces en la lista, sumamos la cantidad
                        $attachData[$productId]['quantity'] += $item['quantity'];
                    } else {
                        $attachData[$productId] = [
                            'quantity'      => $item['quantity'],
                            'price'         => $item['price'],
                            'selling_price' => $item['selling_price'],
                        ];
                    }

                    // Actualizar producto: incrementamos stock Y actualizamos precio de venta actual
                    $product = Product::findOrFail($productId);
                    $product->increment('stock', $item['quantity']);
                    $product->update([
                        'price' => $item['selling_price'] // Asigna el nuevo precio al catálogo público
                    ]);
                }

                // Paso C: Adjuntar a la tabla pivote
                $purchase->products()->attach($attachData);
            });

            return redirect()->route('admin.purchases.index')
                ->with('success', '¡La compra ha sido registrada con éxito!');
        } catch (\Exception $e) {
            Log::error('Error al registrar compra: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la compra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('products');

        return response()->json($purchase);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        if ($purchase->status === 'canceled') {
            return response()->json([
                'success' => false,
                'message' => 'Esta compra ya fue anulada previamente,'
            ], 400);
        }
        try {
            DB::transaction(function () use ($purchase) {
                // 1. (Opcional) Revertir el stock de cada producto
                foreach ($purchase->products as $product) {
                    \App\Models\Product::where('id', $product->id)
                        ->decrement('stock', $product->pivot->quantity);
                }

                // 2. Desvincular productos de la tabla pivote
                $purchase->update([
                    'status' => 'canceled'
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Compra anulada y stock revertido correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al intentar anular la compra.' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        if ($purchase->status === 'canceled') {
            return response()->json([
                'success' => false,
                'message' => 'Esta compra ya fue anulada previamente,'
            ], 400);
        }
        try {
            DB::transaction(function () use ($purchase) {
                // 1. (Opcional) Revertir el stock de cada producto
                foreach ($purchase->products as $product) {
                    \App\Models\Product::where('id', $product->id)
                        ->decrement('stock', $product->pivot->quantity);
                }

                // 2. Desvincular productos de la tabla pivote
                $purchase->update([
                    'status' => 'canceled'
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Compra anulada y stock revertido correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al intentar anular la compra.' . $e->getMessage()
            ], 500);
        }
    }
}
