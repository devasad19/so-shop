<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::withCount('products')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.owner.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:product_categories,name'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'ক্যাটাগরির নাম লিখুন।',
            'name.unique' => 'এই ক্যাটাগরিটি ইতিমধ্যে আছে।',
        ]);

        ProductCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) ?: uniqid('category-'),
            'description' => $validated['description'] ?? null,
            'status' => true,
        ]);

        return redirect()->route('owner.categories.index')
            ->with('success', 'ক্যাটাগরি যোগ করা হয়েছে।');
    }

    public function update(Request $request, ProductCategory $category)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:product_categories,name,' . $category->id,
            ],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'ক্যাটাগরির নাম লিখুন।',
            'name.unique' => 'এই ক্যাটাগরিটি ইতিমধ্যে আছে।',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) ?: uniqid('category-'),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('owner.categories.index')
            ->with('success', 'ক্যাটাগরি আপডেট করা হয়েছে।');
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'এই ক্যাটাগরিতে পণ্য আছে। তাই ক্যাটাগরিটি মুছে ফেলা যাবে না।'
            );
        }

        $category->delete();

        return redirect()->route('owner.categories.index')
            ->with('success', 'ক্যাটাগরি মুছে ফেলা হয়েছে।');
    }
}
