<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessUser;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $membership = BusinessUser::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$membership) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'আপনার কোনো সক্রিয় দোকান পাওয়া যায়নি।',
                ]);
        }

        $business = Business::where('id', $membership->business_id)
            ->where('owner_user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$business) {
            return view('backend.owner.suspended');
        }

        return view('backend.owner.dashboard', compact(
            'user',
            'business',
            'membership'
        ));
    }
}
