<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Business;
use App\Models\BusinessUser;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'আপনার নাম লিখুন।',
            'business_name.required' => 'দোকানের নাম লিখুন।',
            'phone.required' => 'মোবাইল নম্বর লিখুন।',
            'email.required' => 'ইমেইল লিখুন।',
            'email.email' => 'সঠিক ইমেইল লিখুন।',
            'email.unique' => 'এই ইমেইল দিয়ে ইতোমধ্যে অ্যাকাউন্ট আছে।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড নিশ্চিতকরণ মিলছে না।',
        ]);

        $user = DB::transaction(function () use ($validated) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $slugBase = Str::slug($validated['business_name']);
            $slug = $slugBase;
            $counter = 1;

            while (Business::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }

            $business = Business::create([
                'name' => $validated['business_name'],
                'slug' => $slug,
                'business_type' => 'general',
                'owner_user_id' => $user->id,
                'phone' => $validated['phone'],
                'status' => 'active',
                'currency' => 'BDT',
            ]);

            BusinessUser::create([
                'business_id' => $business->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'status' => 'active',
                'joined_at' => now(),
            ]);

            Store::create([
                'business_id' => $business->id,
                'name' => $validated['business_name'],
                'phone' => $validated['phone'],
                'store_type' => 'shop',
                'is_default' => true,
                'status' => true,
            ]);

            return $user;
        });

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('owner.dashboard');
    }


    public function showLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'ইমেইল লিখুন।',
            'password.required' => 'পাসওয়ার্ড লিখুন।',
        ]);

        if (!Auth::attempt(
            $validated,
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'email' => 'ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $membership = BusinessUser::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$membership) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'এই অ্যাকাউন্টের কোনো সক্রিয় দোকান পাওয়া যায়নি।',
                ]);
        }

        $business = Business::find($membership->business_id);

        if (!$business || $business->status !== 'active') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'আপনার দোকানের অ্যাকাউন্ট বর্তমানে সক্রিয় নেই।',
                ]);
        }

        return redirect()->route('owner.dashboard');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.home');
    }
}
