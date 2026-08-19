<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1"><i class="bi bi-sliders me-2"></i>Pengaturan Watermark</h3>
            <p class="text-secondary mb-0">Atur tampilan dan posisi watermark untuk melindungi hak cipta foto Anda.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-20 text-success mb-4"
            role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card p-4">
                <form wire:submit.prevent="save">
                    <!-- Logo / Gambar Watermark -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold">Upload Logo Watermark (PNG Transparan
                            Disarankan)</label>
                        <input type="file" class="form-control bg-dark border-secondary text-white"
                            wire:model="watermark_image" accept="image/*">
                        @error('watermark_image')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror

                        @if ($watermark_image)
                            <div class="mt-3 p-3 bg-dark rounded border border-secondary text-center">
                                <small class="text-secondary d-block mb-2">Preview Logo Baru:</small>
                                <img src="{{ $watermark_image->temporaryUrl() }}" style="max-height: 80px;"
                                    alt="Watermark Preview">
                            </div>
                        @elseif($current_image)
                            <div class="mt-3 p-3 bg-dark rounded border border-secondary text-center">
                                <small class="text-secondary d-block mb-2">Logo Watermark Saat Ini:</small>
                                <img src="{{ asset('storage/' . $current_image) }}" style="max-height: 80px;"
                                    alt="Current Watermark">
                            </div>
                        @endif
                    </div>

                    <!-- Posisi Watermark -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold">Posisi Watermark pada Foto</label>
                        <select class="form-select bg-dark border-secondary text-white" wire:model="position">
                            <option value="center">Tengah (Center)</option>
                            <option value="top-left">Kiri Atas (Top-Left)</option>
                            <option value="top-right">Kanan Atas (Top-Right)</option>
                            <option value="bottom-left">Kiri Bawah (Bottom-Left)</option>
                            <option value="bottom-right">Kanan Bawah (Bottom-Right)</option>
                        </select>
                        @error('position')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Transparansi (Opacity) -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold">Transparansi Watermark: <span
                                class="text-primary fw-bold">{{ $opacity }}%</span></label>
                        <input type="range" class="form-range" min="10" max="100" step="5"
                            wire:model.live="opacity">
                        @error('opacity')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Skala Ukuran Watermark -->
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-semibold">Skala Ukuran Watermark (% dari ukuran
                            foto): <span class="text-primary fw-bold">{{ $scale }}%</span></label>
                        <input type="range" class="form-range" min="5" max="80" step="5"
                            wire:model.live="scale">
                        @error('scale')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="bi bi-save me-2"></i>Simpan Pengaturan</span>
                            <span wire:loading><i class="bi bi-arrow-repeat spin me-2"></i>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Mockup -->
        <div class="col-lg-5 mt-4 mt-lg-0">
            <div class="card p-4 text-center">
                <h5 class="text-white fw-bold mb-3"><i class="bi bi-eye me-2"></i>Simulasi Tampilan</h5>
                <p class="text-secondary small mb-3">Estimasi posisi watermark pada foto saat ditampilkan di website.
                </p>

                <div class="position-relative d-inline-block rounded overflow-hidden border border-secondary bg-dark w-100"
                    style="height: 240px;">
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary opacity-50">
                        <i class="bi bi-image display-1"></i>
                    </div>

                    <!-- Simulated Watermark Overlay -->
                    @if ($watermark_image || $current_image)
                        @php
                            $posClass = match ($position) {
                                'top-left' => 'top-0 start-0 m-3',
                                'top-right' => 'top-0 end-0 m-3',
                                'bottom-left' => 'bottom-0 start-0 m-3',
                                'bottom-right' => 'bottom-0 end-0 m-3',
                                default => 'top-50 start-50 translate-middle',
                            };
                        @endphp
                        <div class="position-absolute {{ $posClass }}"
                            style="opacity: {{ $opacity / 100 }}; max-width: {{ $scale }}%;">
                            <img src="{{ $watermark_image ? $watermark_image->temporaryUrl() : asset('storage/' . $current_image) }}"
                                class="img-fluid" alt="Preview Watermark Overlay">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
