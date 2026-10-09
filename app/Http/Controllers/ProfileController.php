<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        return view('pages.customer.profile', [
            'orders' => $request->session()->get('orders', []),
            'wishlistCount' => count($request->session()->get('wishlist', [])),
        ]);
    }

    public function addresses(Request $request)
    {
        return view('pages.customer.addresses', ['addresses' => $request->session()->get('addresses', [])]);
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
        $addresses = $request->session()->get('addresses', []);
        $addresses[] = $address;
        $request->session()->put('addresses', $addresses);

        return to_route('addresses.index')->with('status', 'Address saved.');
    }
}
