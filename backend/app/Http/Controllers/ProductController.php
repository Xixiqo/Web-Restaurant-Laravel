<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     * GET /api/products
     */
    public function index(): JsonResponse
    {
        $products = Product::all()->map(function ($product) {
            return $this->formatProduct($product);
        });

        return response()->json($products);
    }

    /**
     * Store a newly created product.
     * POST /api/products
     */
    public function store(Request $request): JsonResponse
    {
        // Validate incoming request
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Create the product
        $product = Product::create($validated);

        return response()->json($this->formatProduct($product), 201);
    }

    /**
     * Display the specified product.
     * GET /api/products/{id}
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        return response()->json($this->formatProduct($product));
    }

    /**
     * Update the specified product.
     * PUT/PATCH /api/products/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        // Validate incoming request
        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'rating'      => 'nullable|numeric|min:0|max:5',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload — delete old image if replacing
        if ($request->hasFile('image')) {
            // Delete old image from storage if it was a local file
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update the product
        $product->update($validated);

        return response()->json($this->formatProduct($product));
    }

    /**
     * Remove the specified product.
     * DELETE /api/products/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        // Delete image from storage if it was a local file
        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Format a product for JSON response.
     * Includes the computed image_url field.
     */
    private function formatProduct(Product $product): array
    {
        return [
            'id'          => $product->id,
            'name'        => $product->name,
            'description' => $product->description,
            'image'       => $product->image_url, // Use the accessor for full URL
            'price'       => (float) $product->price,
            'rating'      => (float) $product->rating,
            'created_at'  => $product->created_at,
            'updated_at'  => $product->updated_at,
        ];
    }
}
