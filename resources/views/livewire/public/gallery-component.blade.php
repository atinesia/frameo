<div class="container py-5">
    <!-- Header / Hero -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-white display-5">Galeri Eksklusif Studio</h1>
        <p class="text-secondary">Temukan foto hasil jepretan Anda. Pilih foto, lakukan pembayaran aman, dan unduh file
            aslinya tanpa watermark.</p>
    </div>

    <!-- Filter & Search -->
    <div class="row g-3 mb-5 justify-content-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-secondary"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-dark border-secondary text-white"
                    placeholder="Cari nama album..." wire:model.live="search">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select bg-dark border-secondary text-white" wire:model.live="album_id">
                <option value="">Semua Album</option>
                @foreach ($albums as $album)
                    <option value="{{ $album->id }}">{{ $album->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Photo Grid (Protected) -->
    <div class="row g-4">
        @forelse($photos as $photo)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 bg-dark border-secondary overflow-hidden shadow-sm" style="border-radius: 12px;">
                    <div class="position-relative ratio ratio-4x3 bg-black">
                        <!-- Watermarked Image with Anti-Download Protection -->
                        <img src="{{ asset('storage/' . $photo->watermark_path) }}"
                            class="w-100 h-100 object-fit-cover user-select-none" style="pointer-events: none;"
                            oncontextmenu="return false;" alt="Protected Photo">

                        <!-- Overlay Action -->
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex justify-content-between align-items-center"
                            style="background: linear-gradient(to top, rgba(15,23,42,0.9), transparent);">
                            <span class="text-white fw-bold">Rp {{ number_format($photo->price, 0, ',', '.') }}</span>
                            <button class="btn btn-sm btn-primary fw-semibold px-3"
                                wire:click="showPhotoDetail({{ $photo->id }})">
                                <i class="bi bi-bag-plus me-1"></i> Beli Foto
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-secondary mb-3"><i class="bi bi-camera-fill display-1"></i></div>
                <h5 class="text-secondary">Belum ada foto yang tersedia</h5>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $photos->links() }}
    </div>

    <!-- Modal Detail Pembelian -->
    @if ($selectedPhoto)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.8);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-dark border-secondary text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title fw-bold">Konfirmasi Pembelian Foto</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="mb-3 ratio ratio-16x9 bg-black rounded overflow-hidden">
                            <img src="{{ asset('storage/' . $selectedPhoto->watermarked_path) }}"
                                class="object-fit-contain user-select-none" style="pointer-events: none;"
                                oncontextmenu="return false;" alt="Preview">
                        </div>
                        <h4 class="text-primary fw-bold mb-2">Rp {{ number_format($selectedPhoto->price, 0, ',', '.') }}
                        </h4>
                        <p class="text-secondary small mb-0">Album: {{ $selectedPhoto->album->title }}</p>
                        <p class="text-secondary small">File asli beresolusi tinggi tanpa watermark akan dikirim
                            otomatis via Email & WhatsApp setelah pembayaran terverifikasi.</p>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary px-4" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-success px-4 fw-bold">
                            <i class="bi bi-credit-card me-2"></i>Lanjut ke Tripay Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
