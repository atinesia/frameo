<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-white mb-1"><i class="bi bi-journal-album me-2"></i>Manajemen Album</h3>
            <p class="text-secondary mb-0">Kelola album foto karya Anda di sini</p>
        </div>
        <button class="btn btn-primary px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#albumModal"
            wire:click="create">
            <i class="bi bi-plus-lg me-2"></i>Tambah Album Baru
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show border-0 bg-success bg-opacity-20 text-success mb-4"
            role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-secondary"><i
                        class="bi bi-search"></i></span>
                <input type="text" class="form-control bg-dark border-secondary text-white"
                    placeholder="Cari album..." wire:model.live="search">
            </div>
        </div>
    </div>

    <!-- Album Grid -->
    <div class="row g-4">
        @forelse($albums as $album)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 overflow-hidden shadow-sm">
                    <div class="position-relative" style="height: 180px; background-color: #0f172a;">
                        @if ($album->cover_image)
                            <img src="{{ asset('storage/' . $album->cover_image) }}"
                                class="w-100 h-100 object-fit-cover" alt="{{ $album->title }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <span
                            class="badge bg-dark bg-opacity-75 text-white position-absolute top-0 end-0 m-2 px-2 py-1">
                            <i class="bi bi-camera me-1"></i>{{ $album->photos_count }} Foto
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-white mb-2">{{ $album->title }}</h5>
                        <p class="card-text text-secondary small flex-grow-1">
                            {{ Str::limit($album->description ?? 'Tidak ada deskripsi', 80) }}
                        </p>
                        <div class="d-flex gap-2 mt-3 pt-2 border-top border-secondary border-opacity-25">
                            <button class="btn btn-sm btn-outline-warning w-50" data-bs-toggle="modal"
                                data-bs-target="#albumModal" wire:click="edit({{ $album->id }})">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger w-50"
                                onclick="confirm('Yakin ingin menghapus album ini?') || event.stopImmediatePropagation()"
                                wire:click="delete({{ $album->id }})">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-secondary mb-3"><i class="bi bi-folder-x display-1"></i></div>
                <h5 class="text-secondary">Belum ada album yang ditambahkan</h5>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $albums->links() }}
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="albumModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-secondary text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold">{{ $isEditMode ? 'Edit Album' : 'Tambah Album Baru' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-secondary">Judul Album</label>
                            <input type="text"
                                class="form-control bg-secondary bg-opacity-10 border-secondary text-white"
                                wire:model="title" placeholder="Contoh: Wedding A & B">
                            @error('title')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Deskripsi</label>
                            <textarea class="form-control bg-secondary bg-opacity-10 border-secondary text-white" wire:model="description"
                                rows="3" placeholder="Keterangan album..."></textarea>
                            @error('description')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">Cover Album</label>
                            <input type="file"
                                class="form-control bg-secondary bg-opacity-10 border-secondary text-white"
                                wire:model="cover_image" accept="image/*">
                            @error('cover_image')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror

                            @if ($cover_image)
                                <div class="mt-2">
                                    <small class="text-secondary d-block mb-1">Preview Cover Baru:</small>
                                    <img src="{{ $cover_image->temporaryUrl() }}" class="img-thumbnail bg-dark"
                                        style="max-height: 120px;">
                                </div>
                            @elseif($existing_cover)
                                <div class="mt-2">
                                    <small class="text-secondary d-block mb-1">Cover Saat Ini:</small>
                                    <img src="{{ asset('storage/' . $existing_cover) }}" class="img-thumbnail bg-dark"
                                        style="max-height: 120px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <span wire:loading.remove>{{ $isEditMode ? 'Simpan Perubahan' : 'Buat Album' }}</span>
                            <span wire:loading><i class="bi bi-arrow-repeat spin me-1"></i>Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
