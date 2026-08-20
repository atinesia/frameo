<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h3 class="fw-bold text-white mb-4"><i class="bi bi-box-seam me-2 text-primary"></i>Checkout Paket Album
                (Bundle)</h3>

            @if (session()->has('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            <div class="row g-4">
                <!-- Ringkasan Bundle -->
                <div class="col-md-5">
                    <div class="card bg-dark border-secondary p-4 text-center">
                        <i class="bi bi-journal-album display-1 text-primary mb-3"></i>
                        <h4 class="text-white fw-bold mb-1">{{ $bundle->name }}</h4>
                        <p class="text-secondary small mb-3">Album: {{ $bundle->album->title }}
                            ({{ $bundle->album->photos->count() }} Foto)</p>
                        <h2 class="text-primary fw-bold mb-0">Rp {{ number_format($bundle->price, 0, ',', '.') }}</h2>
                        <small class="text-muted d-block mt-3">*Semua foto dalam album akan dikemas dalam format .ZIP
                            otomatis setelah lunas.</small>
                    </div>
                </div>

                <!-- Form Pemesanan -->
                <div class="col-md-7">
                    <div class="card bg-dark border-secondary p-4">
                        <form wire:submit.prevent="processPayment">
                            <h5 class="text-white fw-bold mb-3">Data Pembeli</h5>
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Nama Lengkap</label>
                                <input type="text"
                                    class="form-control bg-secondary bg-opacity-10 border-secondary text-white"
                                    wire:model="customer_name">
                                @error('customer_name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small">Email</label>
                                <input type="email"
                                    class="form-control bg-secondary bg-opacity-10 border-secondary text-white"
                                    wire:model="customer_email">
                                @error('customer_email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-secondary small">No. WhatsApp</label>
                                <input type="text"
                                    class="form-control bg-secondary bg-opacity-10 border-secondary text-white"
                                    wire:model="customer_phone">
                                @error('customer_phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <h5 class="text-white fw-bold mb-3">Pilih Pembayaran</h5>
                            <div class="row g-2 mb-4" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($payment_channels as $channel)
                                    @if ($channel['active'])
                                        <div class="col-6">
                                            <label
                                                class="card p-2 bg-secondary bg-opacity-10 border-secondary custom-radio-card cursor-pointer">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" wire:model="payment_method"
                                                        value="{{ $channel['code'] }}">
                                                    <span
                                                        class="text-white small text-truncate">{{ $channel['name'] }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @error('payment_method')
                                <span class="text-danger small d-block mb-3">{{ $message }}</span>
                            @enderror

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Bayar Bundle
                                Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
