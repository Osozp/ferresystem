<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        $sales = Sale::with('products')->latest()->get();
        return view('admin.sales.index', compact('products', 'sales'));
    }

    public function create()
    {
        $products = Product::all();
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
                // 1. Crear la Venta
                $sale = Sale::create([
                    'user_id'   => Auth::id(),
                    'total'     => $request->total,
                    'sale_date' => $request->sale_date ?? now(),
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
                ->with('success', '¡La compra ha sido registrada con éxito!');
        } catch (Exception $e) {
            // Guardar el error detallado en el log de Laravel
            //Log::error('Error al registrar venta: ' , $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la compra: ' . $e->getMessage());
        }
    }
}
