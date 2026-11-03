<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data['name'] = Str::ucfirst($data['name']);

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria creada correctamnete');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $products = $category->products()->paginate(10);
        return view('admin.categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255' . $category->id,
        ]);

        $data['name'] = Str::ucfirst($data['name']);

        $category->update($data);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()){
            return redirect()->back()->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }
        $category->delete();

        return redirect()->route('admin.categories.index');
    }
}
