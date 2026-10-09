<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            if (! Schema::hasColumn('products', 'author')) {
                $table->string('author')->nullable()->after('name');
            }

            if (! Schema::hasColumn('products', 'isbn')) {
                $table->string('isbn')->nullable()->unique()->after('author');
            }

            if (! Schema::hasColumn('products', 'publisher')) {
                $table->string('publisher')->nullable()->after('isbn');
            }

            if (! Schema::hasColumn('products', 'page_count')) {
                $table->unsignedInteger('page_count')->nullable()->after('publisher');
            }

            if (! Schema::hasColumn('products', 'language')) {
                $table->string('language', 10)->default('id')->after('page_count');
            }

            if (Schema::hasColumn('products', 'discount_price') && ! Schema::hasColumn('products', 'compare_price')) {
                $table->renameColumn('discount_price', 'compare_price');
            }
        });

        if (Schema::hasColumn('products', 'material')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('material');
            });
        }

        Schema::table('product_variants', function (Blueprint $table): void {
            if (! Schema::hasColumn('product_variants', 'format')) {
                $table->string('format')->default('paperback')->after('sku');
            }
        });

        if (Schema::hasColumn('product_variants', 'color')) {
            Schema::table('product_variants', function (Blueprint $table): void {
                $table->dropUnique('product_variants_product_id_color_size_unique');
                $table->dropColumn(['color', 'size']);
                $table->unique(['product_id', 'format']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table): void {
            if (Schema::hasColumn('product_variants', 'format')) {
                $table->dropUnique('product_variants_product_id_format_unique');
                $table->string('color')->nullable();
                $table->string('size')->nullable();
                $table->dropColumn('format');
                $table->unique(['product_id', 'color', 'size']);
            }
        });

        Schema::table('products', function (Blueprint $table): void {
            if (Schema::hasColumn('products', 'compare_price')) {
                $table->renameColumn('compare_price', 'discount_price');
            }

            $table->text('material')->nullable();
            $table->dropColumn(['author', 'isbn', 'publisher', 'page_count', 'language']);
        });
    }
};
