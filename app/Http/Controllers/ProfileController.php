<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = Auth::user();

        return view('pages.customer.profile', [
            'user' => $user,
            'orders' => $user->orders()->count(),
            'wishlistCount' => $user->wishlist?->items()->count() ?? 0,
        ]);
    }

    public function addresses(Request $request)
    {
        return view('pages.customer.addresses', [
            'addresses' => Auth::user()->addresses()->orderByDesc('is_default')->get(),
        ]);
    }

    public function storeAddress(Request $request)
    {
        $address = $request->validate([
            'label' => ['required', 'string', 'max:30'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:80'],
            'postal_code' => ['required', 'digits:5'],
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'label' => $address['label'],
            'recipient_name' => $address['recipient_name'],
            'phone' => $address['phone'],
            'province' => 'DKI Jakarta',
            'city' => $address['city'],
            'district' => '-',
            'postal_code' => $address['postal_code'],
            'full_address' => $address['address'],
            'is_default' => Auth::user()->addresses()->count() === 0,
        ]);

        return to_route('addresses.index')->with('status', 'Address saved.');
    }
}
