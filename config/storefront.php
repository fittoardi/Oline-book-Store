<?php

return [
    'brand' => [
        'name' => 'Kembang Books',
        'tagline' => 'Stories for every state of mind.',
        'email' => 'hello@kembangbooks.test',
    ],
    'categories' => [
        ['slug' => 'fiction', 'name' => 'Fiction', 'description' => 'Stories that stay with you after the last page.', 'tone' => 'from-[#d8c4bd] via-[#efe3d9] to-[#b99a62]'],
        ['slug' => 'nonfiction', 'name' => 'Nonfiction', 'description' => 'Ideas, essays, and lived wisdom for curious minds.', 'tone' => 'from-[#e8cfcf] via-[#f4e7e2] to-[#c58f9d]'],
        ['slug' => 'business', 'name' => 'Business', 'description' => 'Practical thinking for better work and better ideas.', 'tone' => 'from-[#b99a62] via-[#d2b99b] to-[#6f5146]'],
        ['slug' => 'children', 'name' => 'Children', 'description' => 'Bright worlds and gentle adventures for young readers.', 'tone' => 'from-[#d7c1b4] via-[#f4e9dd] to-[#a88b7b]'],
    ],
    'products' => [
        ['id' => 1, 'slug' => 'the-quiet-atlas', 'name' => 'The Quiet Atlas', 'category' => 'Fiction', 'price' => 149000, 'old_price' => 179000, 'rating' => 4.9, 'reviews' => 128, 'tag' => 'Bestseller', 'color' => 'from-[#d8c4bd] via-[#efe3d9] to-[#b99a62]', 'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=900&q=85', 'description' => 'A tender novel about maps, memory, and the places we call home.'],
        ['id' => 2, 'slug' => 'letters-from-tomorrow', 'name' => 'Letters From Tomorrow', 'category' => 'Fiction', 'price' => 119000, 'old_price' => null, 'rating' => 4.8, 'reviews' => 96, 'tag' => 'New arrival', 'color' => 'from-[#e8cfcf] via-[#f4e7e2] to-[#c58f9d]', 'image' => 'https://images.unsplash.com/photo-1511108690759-009324a90311?auto=format&fit=crop&w=900&q=85', 'description' => 'A hopeful collection of stories about choices, time, and second chances.'],
        ['id' => 3, 'slug' => 'the-useful-day', 'name' => 'The Useful Day', 'category' => 'Nonfiction', 'price' => 159000, 'old_price' => 189000, 'rating' => 4.9, 'reviews' => 74, 'tag' => 'Editor pick', 'color' => 'from-[#b99a62] via-[#d2b99b] to-[#6f5146]', 'image' => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=900&q=85', 'description' => 'Small, practical ways to make room for focus, rest, and meaningful work.'],
        ['id' => 4, 'slug' => 'build-better-ideas', 'name' => 'Build Better Ideas', 'category' => 'Business', 'price' => 59000, 'old_price' => null, 'rating' => 4.7, 'reviews' => 52, 'tag' => null, 'color' => 'from-[#d7c1b4] via-[#f4e9dd] to-[#a88b7b]', 'image' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=85', 'description' => 'A clear field guide for turning early thoughts into useful products and teams.'],
        ['id' => 5, 'slug' => 'the-wild-garden', 'name' => 'The Wild Garden', 'category' => 'Children', 'price' => 129000, 'old_price' => null, 'rating' => 4.8, 'reviews' => 41, 'tag' => 'New arrival', 'color' => 'from-[#f5eadc] via-[#fffaf4] to-[#d8c8b7]', 'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=85', 'description' => 'An illustrated journey through a garden where every small creature has a story.'],
        ['id' => 6, 'slug' => 'stories-for-slow-sundays', 'name' => 'Stories for Slow Sundays', 'category' => 'Children', 'price' => 109000, 'old_price' => null, 'rating' => 4.8, 'reviews' => 63, 'tag' => null, 'color' => 'from-[#c6b3c6] via-[#eee2ec] to-[#8f7895]', 'image' => 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=900&q=85', 'description' => 'Warm, illustrated stories made for reading together at the end of a long week.'],
    ],
    'reviews' => [
        ['name' => 'Nadia Rahma', 'initials' => 'NR', 'rating' => 5, 'product' => 'The Quiet Atlas', 'quote' => 'A beautiful story and the paper quality feels wonderful. It arrived carefully packed.'],
        ['name' => 'Alya Putri', 'initials' => 'AP', 'rating' => 5, 'product' => 'The Useful Day', 'quote' => 'Clear, thoughtful, and genuinely useful. I keep coming back to a few pages every week.'],
        ['name' => 'Salsa Mahira', 'initials' => 'SM', 'rating' => 4, 'product' => 'The Wild Garden', 'quote' => 'The illustrations are lovely and it has become our favourite bedtime book.'],
    ],
];
