<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::all()->map(function ($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'image' => $product->image ? asset('storage/' . $product->image) : null,
        ];
    });

    return response()->json($products, 200);
}


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'quantity.required' => 'La cantidad es obligatoria.',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'image' => $imagePath,
            ]);

            return response()->json([
                'message' => 'Producto creado con éxito.',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                ],
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error al crear producto: ' . $e->getMessage());
            return response()->json([
                'error' => 'No se pudo crear el producto.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            \Log::info('Datos recibidos para actualizar:', $request->all()); // Log datos recibidos
    
            $product = Product::findOrFail($id);
    
            $validatedData = $request->validate([
                'name' => 'required_with:price,quantity,image|string|max:255',
                'price' => 'required_with:name,quantity,image|numeric|min:0',
                'quantity' => 'required_with:name,price,image|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            if ($request->hasFile('image')) {
                if ($product->image && \Storage::exists('public/' . $product->image)) {
                    \Storage::delete('public/' . $product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }
    
            $product->name = $validatedData['name'] ?? $product->name;
            $product->price = $validatedData['price'] ?? $product->price;
            $product->quantity = $validatedData['quantity'] ?? $product->quantity;
    
            $product->save();
    
            \Log::info('Producto guardado correctamente:', $product->toArray());
    
            return response()->json([
                'message' => 'Producto actualizado con éxito.',
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $product->quantity,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                ],
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar producto: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al actualizar el producto.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->image && \Storage::exists('public/' . $product->image)) {
                \Storage::delete('public/' . $product->image);
            }

            $product->delete();

            return response()->json(['message' => 'Producto eliminado con éxito.'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar producto: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}