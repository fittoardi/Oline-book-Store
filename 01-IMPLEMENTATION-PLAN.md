# Implementation Plan - Kembang Books

## Scope

Build the customer-facing storefront only. Filament admin resources, real Tripay integration, production shipping APIs, and WhatsApp delivery are deferred. The current implementation uses Laravel 13, Blade, Tailwind CSS, Vite, Alpine.js, Eloquent, and session-backed demo transaction state while database services are completed.

## Delivery Order

1. Foundation: migrations, models, relationships, factories, book seed data, auth guard, and design tokens.
2. Shell: announcement strip, top navbar, footer, responsive page frame, error layout, and shared arcade buttons/cards/tags.
3. Catalog: home, genre index/detail, shop search/filter/sort, collection, book detail, ratings, and related titles.
4. Customer account: register, login, logout, profile, wishlist, saved addresses, order history, order detail, and payment page.
5. Commerce: cart add/update/remove, checkout address/shipping/payment steps, voucher/promotion calculation, order snapshots, and stock rules.
6. Customer care: contact form, recommendation chat, review display/form, notifications, and help states.
7. Hardening: SEO metadata, accessibility, empty/loading/error/success states, responsive QA, performance, and automated tests.

## Current Route Contract

```text
GET  /                         home
GET  /shop                     products.index
GET  /shop/{slug}              products.show
GET  /categories               categories.index
GET  /categories/{slug}        categories.show
GET  /collection               collection.index
GET  /about                    about
GET  /contact                  contact
POST /contact                  contact.submit
GET  /reviews                  reviews.index
GET  /chat                     chat.index
POST /chat                     chat.submit
GET  /login                    login
POST /login                    login.store
GET  /register                 register
POST /register                 register.store
POST /logout                   logout
GET/POST/PATCH/DELETE /cart    cart flow
GET/POST /checkout             checkout flow
GET/POST /wishlist             wishlist flow
GET/POST /addresses            address flow
GET /profile                   profile.index
GET /orders                    orders.index
GET /orders/{number}           orders.show
GET /orders/{number}/payment   payments.show
```

## Data Rules

- Categories are genres: Fiction, Nonfiction, Business, Children, Poetry.
- Products are books with author, synopsis, format, price, cover, and stock.
- Stock belongs to a product variant or edition.
- Cart quantities cannot exceed stock.
- Order items store title, edition, SKU, price, and quantity snapshots.
- Payment status is independent from order status.
- Reviews count only after moderation.
- Customer routes must verify authentication and ownership before database access.

## Definition of Done

A feature is complete when its UI is responsive, validated, accessible, escaped, covered by a feature test, and verified with `php artisan test`, `npm run build`, and Pint.
