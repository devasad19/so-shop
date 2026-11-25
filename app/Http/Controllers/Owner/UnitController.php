<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BusinessUser;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
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

        $units = Unit::where('business_id', $businessId)
            ->withCount('products')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('short_name', 'like', '%' . $search . '%');
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('backend.owner.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $businessId = $this->businessId();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'Unit-এর নাম লিখুন।',
            'short_name.required' => 'Unit-এর সংক্ষিপ্ত নাম লিখুন।',
        ]);

        Unit::create([
            'business_id' => $businessId,
            'name' => $validated['name'],
            'short_name' => $validated['short_name'],
            'status' => true,
        ]);

        return redirect()->route('owner.units.index')
            ->with('success', 'Unit যোগ করা হয়েছে।');
    }

    public function update(Request $request, Unit $unit)
    {
        abort_unless($unit->business_id == $this->businessId(), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_name' => ['required', 'string', 'max:20'],
        ]);

        $unit->update($validated);

        return redirect()->route('owner.units.index')
            ->with('success', 'Unit আপডেট করা হয়েছে।');
    }

    public function destroy(Unit $unit)
    {
        abort_unless($unit->business_id == $this->businessId(), 404);

        if ($unit->products()->exists()) {
            return back()->with('error', 'এই Unit-এর অধীনে পণ্য আছে। তাই Unit মুছে ফেলা যাবে না।');
        }

        $unit->delete();

        return redirect()->route('owner.units.index')
            ->with('success', 'Unit মুছে ফেলা হয়েছে।');
    }
}
