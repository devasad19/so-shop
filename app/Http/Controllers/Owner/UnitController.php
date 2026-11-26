<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $units = Unit::withCount('products')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('short_name', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.owner.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
            'short_name' => ['required', 'string', 'max:20', 'unique:units,short_name'],
        ], [
            'name.required' => 'Unit-এর নাম লিখুন।',
            'name.unique' => 'এই Unit ইতিমধ্যে আছে।',
            'short_name.required' => 'Unit-এর সংক্ষিপ্ত নাম লিখুন।',
            'short_name.unique' => 'এই সংক্ষিপ্ত নাম ইতিমধ্যে আছে।',
        ]);

        Unit::create([
            'name' => $validated['name'],
            'short_name' => $validated['short_name'],
            'status' => true,
        ]);

        return redirect()->route('owner.units.index')
            ->with('success', 'Unit যোগ করা হয়েছে।');
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:units,name,' . $unit->id,
            ],
            'short_name' => [
                'required',
                'string',
                'max:20',
                'unique:units,short_name,' . $unit->id,
            ],
        ], [
            'name.required' => 'Unit-এর নাম লিখুন।',
            'name.unique' => 'এই Unit ইতিমধ্যে আছে।',
            'short_name.required' => 'Unit-এর সংক্ষিপ্ত নাম লিখুন।',
            'short_name.unique' => 'এই সংক্ষিপ্ত নাম ইতিমধ্যে আছে।',
        ]);

        $unit->update($validated);

        return redirect()->route('owner.units.index')
            ->with('success', 'Unit আপডেট করা হয়েছে।');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->products()->exists()) {
            return back()->with(
                'error',
                'এই Unit-এর অধীনে পণ্য আছে। তাই Unit মুছে ফেলা যাবে না।'
            );
        }

        $unit->delete();

        return redirect()->route('owner.units.index')
            ->with('success', 'Unit মুছে ফেলা হয়েছে।');
    }
}
