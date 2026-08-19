<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1"><i class="bi bi-cloud-upload me-2"></i>Upload Foto Massal</h3>
            <p class="text-secondary mb-0">Upload foto ke dalam album. Sistem akan otomatis memasang watermark pada
                setiap foto.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-20  mb-4"
            role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card p-4">
                <form wire:submit.prevent="save">
                    <!-- Pilih Album -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold">Pilih Album Foto <span
                                class="text-danger">*</span></label>
                        <select class="form-select bg-dark border-secondary text-white" wire:model="album_id">
                            <option value="">-- Pilih Album --</option>
                            @foreach ($albums as $album)
                                <option value="{{ $album->id }}">{{ $album->title }}</option>
                            @endforeach
                        </select>
                        @error('album_id')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Harga Default Per Foto -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold">Harga Standar per Foto (Rp) <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-secondary">Rp</span>
                            <input type="number" class="form-control bg-dark border-secondary text-white"
                                wire:model="default_price" placeholder="15000">
                        </div>
                        @error('default_price')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Multiple Photo -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold">Pilih Foto (Bisa Pilih Banyak Foto) <span
                                class="text-danger">*</span></label>
                        <input type="file" class="form-control bg-dark border-secondary text-white"
                            wire:model="photos" multiple accept="image/*">
                        <small class="text-secondary d-block mt-1">Format: JPG, PNG, WEBP (Maksimal 10MB per
                            foto)</small>
                        @error('photos.*')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Preview Foto Sebelum Diproses -->
                    @if ($photos)
                        <div class="mb-4">
                            <h6 class="text-white mb-3">Preview Upload ({{ count($photos) }} Foto Dipilih):</h6>
                            <div class="row g-2" style="max-height: 250px; overflow-y: auto;">
                                @foreach ($photos as $photo)
                                    <div class="col-3 col-md-2">
                                        <div class="ratio ratio-1x1 rounded border border-secondary overflow-hidden">
                                            <img src="{{ $photo->temporaryUrl() }}" class="object-fit-cover"
                                                alt="Preview">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-magic me-2"></i>Upload & Generate Watermark</span>
                            <span wire:loading><i class="bi bi-arrow-repeat spin me-2"></i>Sedang Memproses
                                Foto...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card p-4 border-info bg-info bg-opacity-10">
                <h5 class="text-info fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h5>
                <ul class="text-secondary small mb-0 ps-3">
                    <li class="mb-2">Foto asli yang di-upload akan disimpan secara <strong>aman & tersembunyi</strong>
                        (folder non-publik).</li>
                    <li class="mb-2">Sistem akan secara otomatis menduplikasi foto dan menambahkan watermark sesuai
                        dengan **Pengaturan Watermark** saat ini.</li>
                    <li class="mb-0">Foto ber-watermark inilah yang nantinya akan ditampilkan di galeri publik untuk
                        pengunjung.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
