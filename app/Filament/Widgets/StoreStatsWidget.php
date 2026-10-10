<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $revenue = Order::query()
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $pendingOrders = Order::query()
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        return [
            Stat::make('Total revenue', 'Rp'.number_format((float) $revenue, 0, ',', '.'))
                ->description('Pesanan berstatus paid')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Orders', Order::count())
                ->description($pendingOrders.' menunggu diproses')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color($pendingOrders > 0 ? 'warning' : 'gray'),

            Stat::make('Books', Product::where('is_active', true)->count())
                ->description(Category::where('is_active', true)->count().' kategori aktif')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Customers', User::where('role', 'customer')->count())
                ->description(User::where('role', 'customer')->whereDate('created_at', today())->count().' baru hari ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),

            Stat::make('Reviews', Review::where('is_approved', true)->count())
                ->description(Review::where('is_approved', false)->count().' menunggu moderasi')
                ->descriptionIcon('heroicon-m-star')
                ->color(Review::where('is_approved', false)->count() > 0 ? 'warning' : 'gray'),
        ];
    }
}
