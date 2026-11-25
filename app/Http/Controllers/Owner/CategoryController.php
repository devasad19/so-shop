<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private function businessId()
    {
        $membership = BusinessUser::where('user_id', Auth::id())
            ->where('role', 'owner')
            ->where('status', 'active')
            ->first();

        abort_unless($membership, 403);

        return $membership->business_id;
    }

    public function index(Request $request)
    {
        $businessId = $this->businessId();

        $categories = ProductCategory::where('business_id', $businessId)
            ->withCount('products')
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
        $businessId = $this->businessId();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'ক্যাটাগরির নাম লিখুন।',
        ]);

        ProductCategory::create([
            'business_id' => $businessId,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'status' => true,
        ]);

        return redirect()->route('owner.categories.index')
            ->with('success', 'ক্যাটাগরি যোগ করা হয়েছে।');
    }

    public function update(Request $request, ProductCategory $category)
    {
        abort_unless($category->business_id == $this->businessId(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->route('owner.categories.index')
            ->with('success', 'ক্যাটাগরি আপডেট করা হয়েছে।');
    }

    public function destroy(ProductCategory $category)
    {
        abort_unless($category->business_id == $this->businessId(), 404);

        if ($category->products()->exists()) {
            return back()->with('error', 'এই ক্যাটাগরিতে পণ্য আছে। তাই ক্যাটাগরিটি মুছে ফেলা যাবে না।');
        }

        $category->delete();

        return redirect()->route('owner.categories.index')
            ->with('success', 'ক্যাটাগরি মুছে ফেলা হয়েছে।');
    }
}
