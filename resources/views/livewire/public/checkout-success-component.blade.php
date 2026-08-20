<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark border-secondary p-4 shadow-lg">
                <div class="mb-3 text-success">
                    <i class="bi bi-check-circle-fill display-1"></i>
                </div>
                <h3 class="fw-bold text-white mb-2">Transaksi Diproses!</h3>
                <p class="text-secondary small">Nomor Pesanan: <strong
                        class="text-white">{{ $order->order_number }}</strong></p>

                <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info text-start my-4">
                    <h6 class="fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> Status Pembayaran: <span
                            class="badge bg-warning text-dark text-uppercase">{{ $order->status }}</span></h6>
                    <small>Setelah pembayaran Anda terverifikasi oleh Tripay, sistem akan otomatis mengirimkan link
                        download foto asli tanpa watermark ke Email <strong>{{ $order->customer_email }}</strong> dan
                        WhatsApp <strong>{{ $order->customer_phone }}</strong>.</small>
                </div>

                <a href="{{ route('home') }}" class="btn btn-primary fw-bold w-100 py-2">
                    <i class="bi bi-house me-2"></i>Kembali ke Galeri
                </a>
            </div>
        </div>
    </div>
</div>
