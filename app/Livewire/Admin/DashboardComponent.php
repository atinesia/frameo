<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\Photo;
use App\Models\Album;

class DashboardComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        // Statistik Utama
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        $successfulOrders = Order::where('status', 'paid')->count();
        $pendingOrders = Order::where('status', 'unpaid')->count();
        $totalPhotos = Photo::count();
        $totalAlbums = Album::count();

        // Riwayat Transaksi Terbaru (Paginated)
        $recentOrders = Order::with('items.photo')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.dashboard-component', compact(
            'totalRevenue',
            'successfulOrders',
            'pendingOrders',
            'totalPhotos',
            'totalAlbums',
            'recentOrders'
        ))->layout('layouts.admin');
    }
}
