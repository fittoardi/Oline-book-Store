# Prompt Pack - Kembang Books

Use these prompts to continue building the customer bookstore without reintroducing the previous fashion domain.

## Master Context

You are a senior Laravel and Blade engineer building the customer-facing storefront of **Kembang Books**, a curated single-brand online bookstore. Read `02-PRD.md`, `01-IMPLEMENTATION-PLAN.md`, `04-DESIGN.md`, and `05-DB.md` before changing code.

Use Laravel 13, PHP 8.3+, Blade, Tailwind v4, Vite, Alpine.js, Eloquent, and Pest. Do not build Filament in customer tasks. Keep the light arcade bookstore style: cream canvas, black pixel rules, checkerboard surfaces, orange marquee, green purchase button, compact spacing, and no soft shadows.

All customer content must be escaped. Every form needs `@csrf`, labels, validation errors, preserved input, and keyboard focus. Recalculate price, stock, discounts, and payment state on the server. Add a focused feature test with each behavior change. Finish every task with `php artisan test`, `npm run build`, and Pint.

## Prompt A - Catalog

Implement book catalog behavior. Add title/author/genre search, genre filtering, price range, stock filter, sorting, pagination, empty state, and SEO metadata. Product cards must show cover, title, genre, price, rating, stock, wishlist, and Add to Cart. Use book data from `config/storefront.php` until database queries are ready.

## Prompt B - Book Detail

Implement the book detail page with cover gallery, synopsis, author, genre, format options, stock badge, quantity control, price, Add to Cart, Buy Now, Wishlist, rating summary, approved reviews, and related books. Disable unavailable editions and never trust client totals.

## Prompt C - Cart and Checkout

Implement CartService and CheckoutService around carts, variants, addresses, vouchers, promotions, orders, shipments, and payments. Support add/merge, quantity limits, remove, address selection, shipping options, payment channels, voucher validation, order snapshots, idempotent placement, and empty/error/success states.

## Prompt D - Customer Account

Implement customer-only profile, wishlist, saved addresses, order history, order detail, payment status, tracking, and logout. Apply ownership policies and show order and payment statuses separately.

## Prompt E - Reviews and Care

Implement eligible customer reviews with 1-5 rating, comment, optional safe image upload, moderation state, approved review display, contact form, chat composer, validation, rate limiting, and customer care entry points.

## Prompt F - Hardening

Audit responsive behavior at 360, 768, 1024, and 1440px. Verify keyboard flow, focus states, semantic landmarks, image dimensions, lazy loading, empty/loading/error/success states, SEO tags, and no remaining legacy fashion copy. Run the full test suite and production build.
