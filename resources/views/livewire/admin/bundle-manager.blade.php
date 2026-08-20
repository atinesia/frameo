<div>
    <div class="mb-4">
        <h3 class="fw-bold text-white mb-1"><i class="bi bi-box-seam me-2"></i>Paket Diskon Album (Bundle)</h3>
        <p class="text-secondary mb-0">Atur harga diskon untuk pembeli yang ingin mengunduh seluruh foto di dalam satu
            album.</p>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success bg-success bg-opacity-20 border-0 mb-4">
            <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Tambah Bundle -->
        <div class="col-lg-4">
            <div class="card p-4 border-secondary">
                <h5 class="text-white fw-bold mb-3">Buat Paket Bundle</h5>
                <form wire:submit.prevent="store">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Pilih Album</label>
                        <select class="form-select bg-dark border-secondary text-white" wire:model="album_id">
                            <option value="">-- Pilih Album --</option>
                            @foreach ($albums as $album)
                                <option value="{{ $album->id }}">{{ $album->title }} ({{ $album->photos->count() }}
                                    Foto)</option>
                            @endforeach
                        </select>
                        @error('album_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Nama Paket</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white" wire:model="name"
                            placeholder="Contoh: Paket Full Album Wedding">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Harga Paket Diskon (Rp)</label>
                        <input type="number" class="form-control bg-dark border-secondary text-white"
                            wire:model="price" placeholder="100000">
                        @error('price')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Paket Bundle</button>
                </form>
            </div>
        </div>

        <!-- Daftar Bundle Aktif -->
        <div class="col-lg-8">
            <div class="card p-4 border-secondary">
                <h5 class="text-white fw-bold mb-3">Daftar Paket Bundle</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr class="text-secondary border-secondary">
                                <th>Album</th>
                                <th>Nama Paket</th>
                                <th>Jumlah Foto</th>
                                <th>Harga Paket</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bundles as $bundle)
                                <tr class="border-secondary">
                                    <td class="fw-bold">{{ $bundle->album->title }}</td>
                                    <td>{{ $bundle->name }}</td>
                                    <td>{{ $bundle->photo_count }}</td>
                                    <td class="text-primary fw-bold">Rp {{ number_format($bundle->price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-sm {{ $bundle->is_active ? 'btn-success' : 'btn-secondary' }}"
                                            wire:click="toggleStatus({{ $bundle->id }})">
                                            {{ $bundle->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="confirm('Hapus paket ini?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $bundle->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-3">Belum ada paket bundle
                                        dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
