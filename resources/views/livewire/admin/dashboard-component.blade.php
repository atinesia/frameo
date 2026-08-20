<div>
    <!-- Header -->
    <div class="mb-4">
        <h3 class="fw-bold text-white mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard Analitik</h3>
        <p class="text-secondary mb-0">Ringkasan statistik penjualan foto dan performa bisnis studio Anda.</p>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Pendapatan -->
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 border-0 bg-primary bg-opacity-10 text-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold">Total Pendapatan</span>
                    <i class="bi bi-wallet2 text-primary fs-4"></i>
                </div>
                <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Transaksi Sukses -->
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 border-0 bg-success bg-opacity-10 text-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold">Transaksi Sukses</span>
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
                <h3 class="fw-bold text-success mb-0">{{ $successfulOrders }}</h3>
            </div>
        </div>

        <!-- Transaksi Pending -->
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 border-0 bg-warning bg-opacity-10 text-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold">Menunggu Pembayaran</span>
                    <i class="bi bi-clock-history text-warning fs-4"></i>
                </div>
                <h3 class="fw-bold text-warning mb-0">{{ $pendingOrders }}</h3>
            </div>
        </div>

        <!-- Total Karya Foto -->
        <div class="col-md-6 col-lg-3">
            <div class="card p-3 border-0 bg-info bg-opacity-10 text-white">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-secondary small fw-semibold">Koleksi Foto / Album</span>
                    <i class="bi bi-images text-info fs-4"></i>
                </div>
                <h3 class="fw-bold text-info mb-0">{{ $totalPhotos }} <span
                        class="fs-6 text-secondary">({{ $totalAlbums }} Album)</span></h3>
            </div>
        </div>
    </div>

    <!-- Table Transaksi Terbaru -->
    <div class="card p-4 border-secondary">
        <h5 class="fw-bold text-white mb-3"><i class="bi bi-receipt me-2"></i>Riwayat Transaksi Terbaru</h5>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr class="text-secondary border-secondary">
                        <th>No. Order</th>
                        <th>Pelanggan</th>
                        <th>Kontak (Email / WA)</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-secondary">
                            <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                            <td class="text-white">{{ $order->customer_name }}</td>
                            <td class="small text-secondary">
                                <div><i class="bi bi-envelope me-1"></i>{{ $order->customer_email }}</div>
                                <div><i class="bi bi-whatsapp me-1 text-success"></i>{{ $order->customer_phone }}</div>
                            </td>
                            <td class="fw-semibold text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                @if ($order->status === 'paid')
                                    <span
                                        class="badge bg-success bg-opacity-20 border border-success">Lunas
                                        / Paid</span>
                                @elseif($order->status === 'unpaid')
                                    <span
                                        class="badge bg-warning bg-opacity-20 border border-warning">Pending</span>
                                @else
                                    <span
                                        class="badge bg-danger bg-opacity-20 border border-danger">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="small text-secondary">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Belum ada transaksi yang
                                tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $recentOrders->links() }}
        </div>
    </div>
</div>
