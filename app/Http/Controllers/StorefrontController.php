<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'products' => $this->products(),
            'categories' => config('storefront.categories', []),
            'reviews' => config('storefront.reviews', []),
        ]);
    }

    public function shop(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:40'],
            'min_price' => ['nullable', 'integer', 'min:0'],
            'max_price' => ['nullable', 'integer', 'min:0'],
            'availability' => ['nullable', 'in:in_stock'],
            'sort' => ['nullable', 'in:newest,oldest,price_asc,price_desc,popular,rating'],
        ]);

        $products = collect($this->products())
            ->when($filters['q'] ?? null, fn ($items, $query) => $items->filter(fn ($product) => str_contains(strtolower($product['name'].' '.$product['category']), strtolower($query))))
            ->when($filters['category'] ?? null, fn ($items, $category) => $items->where('category', $category))
            ->when(isset($filters['min_price']), fn ($items) => $items->where('price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn ($items) => $items->where('price', '<=', $filters['max_price']))
            ->when(($filters['availability'] ?? null) === 'in_stock', fn ($items) => $items->filter(fn ($product) => ($product['stock'] ?? 12) > 0));

        $products = match ($filters['sort'] ?? 'newest') {
            'oldest' => $products->sortBy('id'),
            'price_asc' => $products->sortBy('price'),
            'price_desc' => $products->sortByDesc('price'),
            'popular', 'rating' => $products->sortByDesc('rating'),
            default => $products->sortByDesc('id'),
        };

        return view('pages.catalog.shop', [
            'products' => $products->values(),
            'filters' => $filters,
            'categories' => config('storefront.categories', []),
        ]);
    }

    public function collection()
    {
        return view('pages.catalog.collection', ['products' => $this->products()]);
    }

    public function categories()
    {
        return view('categories.index', ['categories' => config('storefront.categories', [])]);
    }

    public function category(string $slug)
    {
        $category = collect(config('storefront.categories', []))->firstWhere('slug', $slug);

        abort_unless($category, 404);

        return view('categories.show', [
            'category' => $category,
            'products' => collect($this->products())->where('category', $category['name']),
        ]);
    }

    public function chat()
    {
        return view('chat.index');
    }

    public function submitChat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        return to_route('chat.index')->with('status', 'Your message is in. The Kembang Books team will reply soon.');
    }

    public function reviews()
    {
        return view('reviews.index', ['reviews' => config('storefront.reviews', [])]);
    }

    public function product(string $slug)
    {
        $products = collect($this->products());
        $product = $products->firstWhere('slug', $slug);

        abort_unless($product, 404);

        return view('pages.catalog.product-show', [
            'product' => $product,
            'relatedProducts' => $products->where('id', '!=', $product['id'])->take(3),
        ]);
    }

    public function cart(Request $request)
    {
        $cart = $this->cartItems($request);

        return view('pages.customer.cart', [
            'cart' => $cart,
            'subtotal' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']),
        ]);
    }

    public function addToCart(Request $request)
    {
        $data = $request->validate([
            'slug' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = collect($this->products())->firstWhere('slug', $data['slug']);
        abort_unless($product, 404);

        $cart = $request->session()->get('cart', []);
        $cart[$product['slug']] = [
            'slug' => $product['slug'],
            'name' => $product['name'],
            'category' => $product['category'],
            'image' => $product['image'],
            'price' => $product['price'],
            'quantity' => ($cart[$product['slug']]['quantity'] ?? 0) + $data['quantity'],
        ];
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', $product['name'].' added to cart.');
    }

    public function updateCart(Request $request, string $slug)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);
        $cart = $request->session()->get('cart', []);

        abort_unless(isset($cart[$slug]), 404);
        $cart[$slug]['quantity'] = $data['quantity'];
        $request->session()->put('cart', $cart);

        return to_route('cart.index');
    }

    public function removeFromCart(Request $request, string $slug)
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$slug]);
        $request->session()->put('cart', $cart);

        return to_route('cart.index')->with('status', 'Item removed from your cart.');
    }

    public function checkout(Request $request)
    {
        $cart = $this->cartItems($request);

        if ($cart === []) {
            return to_route('cart.index');
        }

        return view('pages.customer.checkout', [
            'cart' => $cart,
            'subtotal' => collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']),
            'shipping' => 15000,
            'paymentChannels' => ['Bank transfer', 'E-wallet', 'QRIS', 'COD'],
        ]);
    }

    public function orders(Request $request)
    {
        return view('pages.customer.orders', [
            'orders' => $request->session()->get('orders', []),
        ]);
    }

    public function order(Request $request, string $number)
    {
        $order = collect($request->session()->get('orders', []))->firstWhere('number', $number);

        abort_unless($order, 404);

        return view('pages.customer.order-show', ['order' => $order]);
    }

    public function payment(Request $request, string $number)
    {
        $order = collect($request->session()->get('orders', []))->firstWhere('number', $number);

        abort_unless($order, 404);

        return view('pages.customer.payment', ['order' => $order]);
    }

    public function wishlist(Request $request)
    {
        $slugs = $request->session()->get('wishlist', []);

        return view('pages.customer.wishlist', [
            'products' => collect($this->products())->whereIn('slug', $slugs)->values(),
        ]);
    }

    public function toggleWishlist(Request $request)
    {
        $data = $request->validate(['slug' => ['required', 'string']]);
        abort_unless(collect($this->products())->firstWhere('slug', $data['slug']), 404);

        $wishlist = $request->session()->get('wishlist', []);
        $wishlist = in_array($data['slug'], $wishlist, true)
            ? array_values(array_diff($wishlist, [$data['slug']]))
            : [...$wishlist, $data['slug']];
        $request->session()->put('wishlist', $wishlist);

        return back()->with('status', in_array($data['slug'], $wishlist, true) ? 'Saved to wishlist.' : 'Removed from wishlist.');
    }

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

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'payment' => ['required', 'string', 'in:Bank transfer,E-wallet,QRIS,COD'],
        ]);

        $cart = $this->cartItems($request);

        if ($cart === []) {
            return to_route('cart.index');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $order = [
            'number' => 'KH-'.now()->format('ymdHis'),
            'items' => $cart,
            'subtotal' => $subtotal,
            'shipping' => 15000,
            'total' => $subtotal + 15000,
            'payment' => $data['payment'],
            'customer' => $data,
            'status' => $data['payment'] === 'COD' ? 'Processing' : 'Awaiting payment',
        ];

        $orders = $request->session()->get('orders', []);
        array_unshift($orders, $order);
        $request->session()->put(['orders' => $orders, 'cart' => []]);

        return to_route('orders.index')->with('status', 'Order '.$order['number'].' has been created.');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return to_route('contact')->with('status', 'Your message has been sent. We will get back to you soon.');
    }

    private function products(): array
    {
        return config('storefront.products', []);
    }

    private function cartItems(Request $request): array
    {
        return array_values($request->session()->get('cart', []));
    }
}
