<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h3 class="fw-bold text-white mb-4"><i class="bi bi-shield-check me-2 text-primary"></i>Checkout Pembelian Foto</h3>

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 bg-danger bg-opacity-20  mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Preview Foto & Ringkasan -->
                <div class="col-md-5">
                    <div class="card bg-dark border-secondary p-3 text-center">
                        <div class="ratio ratio-4x3 bg-black rounded overflow-hidden mb-3">
                            <img src="{{ asset('storage/' . $photo->watermark_path) }}" 
                                 class="object-fit-contain user-select-none" 
                                 style="pointer-events: none;" 
                                 oncontextmenu="return false;" 
                                 alt="Photo Preview">
                        </div>
                        <h6 class="text-secondary mb-1">Album: {{ $photo->album->title }}</h6>
                        <h3 class="text-primary fw-bold mb-0">Rp {{ number_format($photo->price, 0, ',', '.') }}</h3>
                        <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                            *File asli tanpa watermark otomatis dikirim via WhatsApp & Email setelah bayar.
                        </small>
                    </div>
                </div>

                <!-- Form Data Pembeli & Metode Pembayaran -->
                <div class="col-md-7">
                    <div class="card bg-dark border-secondary p-4">
                        <form wire:submit.prevent="processPayment">
                            <h5 class="text-white fw-bold mb-3">1. Data Penerima File</h5>
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Nama Lengkap</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10 border-secondary text-white" wire:model="customer_name" placeholder="Contoh: Budi Santoso">
                                @error('customer_name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary small">Email (File asli dikirim ke sini)</label>
                                <input type="email" class="form-control bg-secondary bg-opacity-10 border-secondary text-white" wire:model="customer_email" placeholder="budi@example.com">
                                @error('customer_email') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-secondary small">No. WhatsApp (Format: 08xxx / 628xxx)</label>
                                <input type="text" class="form-control bg-secondary bg-opacity-10 border-secondary text-white" wire:model="customer_phone" placeholder="081234567890">
                                @error('customer_phone') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <h5 class="text-white fw-bold mb-3">2. Pilih Metode Pembayaran</h5>
                            
                            <div class="mb-4" style="max-height: 250px; overflow-y: auto;">
                                <div class="row g-2">
                                    @forelse($payment_channels as $channel)
                                        @if($channel['active'])
                                            <div class="col-6">
                                                <label class="card h-100 p-2 bg-secondary bg-opacity-10 border-secondary custom-radio-card cursor-pointer">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="radio" wire:model="payment_method" value="{{ $channel['code'] }}" class="form-check-input mt-0">
                                                        <img src="{{ $channel['icon_url'] }}" style="height: 20px; object-fit: contain;" alt="{{ $channel['name'] }}">
                                                        <span class="text-white small fw-semibold text-truncate">{{ $channel['name'] }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="col-12 text-center text-muted py-3">Gagal memuat saluran pembayaran.</div>
                                    @endforelse
                                </div>
                                @error('payment_method') <span class="text-danger small d-block mt-2">{{ $message }}</span> @enderror
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold" wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="bi bi-lock-fill me-2"></i>Bayar Sekarang</span>
                                <span wire:loading><i class="bi bi-arrow-repeat spin me-2"></i>Menghubungkan ke Tripay...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>