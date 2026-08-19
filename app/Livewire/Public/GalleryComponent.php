<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Photo;
use App\Models\Album;

class GalleryComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $album_id = '';
    public $selectedPhoto = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingAlbumId()
    {
        $this->resetPage();
    }

    public function showPhotoDetail($photoId)
    {
        $this->selectedPhoto = Photo::with('album')->findOrFail($photoId);
    }

    public function closeModal()
    {
        $this->selectedPhoto = null;
    }

    public function render()
    {
        $query = Photo::with('album');

        if ($this->search) {
            $query->whereHas('album', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->album_id) {
            $query->where('album_id', $this->album_id);
        }

        $photos = $query->latest()->paginate(12);
        $albums = Album::latest()->get();

        return view('livewire.public.gallery-component', compact('photos', 'albums'))
            ->layout('layouts.app');
    }
}
