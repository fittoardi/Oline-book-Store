# PRD - Kembang Books Customer Storefront

**Product:** Kembang Books
**Type:** Single-brand online bookstore
**Platform:** Laravel 13, PHP 8.3+, Blade, Tailwind CSS, Vite, Eloquent
**Audience:** Readers, gift buyers, parents, and curious minds
**Visual direction:** Light retro bookstore kiosk based on `04-DESIGN.md`

## 1. Product Vision

Kembang Books helps customers discover and buy books without the noise of a large marketplace. The storefront should feel like a small, carefully curated shelf: clear, tactile, fast, and easy to browse.

The core customer journey is:

```text
Discover -> Browse genre -> Read detail -> Add to cart -> Checkout -> Pay -> Track -> Review
```

## 2. Catalog

Initial genres:

- Fiction
- Nonfiction
- Business
- Children
- Poetry

Each book includes:

- Title and slug
- Author
- Genre
- Synopsis
- Format: paperback, hardcover, gift edition
- Price and compare-at price
- Cover image and gallery
- Stock status
- Rating and approved review count
- Bestseller, new arrival, or editor pick tag

Listing features:

- Search by title, author, or genre
- Genre filter
- Price range filter
- In-stock filter
- Sort by newest, oldest, price, popularity, and rating
- Empty state when no title matches

## 3. Customer Features

### Authentication

Customers can register, login, logout, and return to their intended page. Registration requires name, email, phone, password confirmation, and terms acceptance. Customer endpoints must not authenticate admin accounts.

### Wishlist

Authenticated customers can save books, remove saved books, and move a saved book to cart. A guest can browse the wishlist entry point but is sent to login before saving.

### Cart

The cart supports add, quantity update, remove, subtotal, promotion hint, and checkout. Quantity must never exceed available stock. The cart must preserve a product snapshot so the UI can show the selected title and price.

### Checkout

Checkout collects recipient details, delivery address, shipping option, voucher/promotion state, payment channel, and order review. Totals are recalculated server-side. Payment channels include bank transfer, e-wallet, QRIS, and optional COD.

### Orders and payment

Customers can see order history, order detail, payment state, shipment information, tracking number, and customer care entry. Order status and payment status are separate values.

### Reviews

A customer can review a purchased book after the order is completed. A review has a 1-5 rating, comment, optional image, and moderation state.

### Customer care

Customers can ask about recommendations, stock, orders, payment, gifts, and delivery through contact and chat pages.

### Profile and addresses

Customers can manage personal details, saved addresses, wishlist, orders, and customer care access.

## 4. Design Requirements

The interface follows `04-DESIGN.md`:

- Arcade cream canvas `#f3e5df`
- Ink black borders and text
- Pixel gray dividers
- Marquee orange `#faa21f` for announcements and accents
- Buy green `#128e44` for purchase actions only
- Checkerboard surfaces for hero/product moments
- 6px buttons, 12px cards, pill tags
- No soft shadows, no dark-mode surfaces, no unnecessary gradients
- Compact responsive layout at 360px, 768px, 1024px, and 1440px
- Every form has labels, CSRF, validation feedback, and keyboard focus states

## 5. Non-functional Requirements

- Server-rendered Blade for SEO
- Alpine.js only for lightweight interaction
- Escaped customer content
- Lazy-loaded images with dimensions
- Paginated database lists
- Eager-loaded Eloquent relationships
- Auth and ownership checks on customer resources
- Server-side price, stock, voucher, and payment validation
- Feature tests for every business rule touched

## 6. Acceptance Criteria

- A visitor can browse home, genres, shop, detail, about, contact, reviews, and customer care.
- A customer can register, login, logout, add a book to cart, checkout, create an order, view payment instructions, and inspect the order.
- Search, genre, price, availability, and sorting work on the shop page.
- Empty, validation, error, and success states are visible and branded.
- The UI is responsive and uses the arcade bookstore visual language.
- `php artisan test`, `npm run build`, and Pint pass.
