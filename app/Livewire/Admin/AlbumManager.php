<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AlbumManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $album_id;
    public $title;
    public $description;
    public $cover_image;
    public $existing_cover;
    public $search = '';
    public $isEditMode = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'cover_image' => 'nullable|image|max:2048',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->album_id = null;
        $this->title = '';
        $this->description = '';
        $this->cover_image = null;
        $this->existing_cover = null;
        $this->isEditMode = false;
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->resetFields();
    }

    public function store()
    {
        $this->validate();

        $coverPath = null;
        if ($this->cover_image) {
            $coverPath = $this->cover_image->store('albums/covers', 'public');
        }

        Album::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . time(),
            'description' => $this->description,
            'cover_photo' => $coverPath,
        ]);

        session()->flash('message', 'Album berhasil dibuat!');
        $this->resetFields();
        $this->dispatch('closeModal');
    }

    public function edit($id)
    {
        $this->resetFields();
        $album = Album::findOrFail($id);

        $this->album_id = $album->id;
        $this->title = $album->title;
        $this->description = $album->description;
        $this->existing_cover = $album->cover_photo;
        $this->isEditMode = true;
    }

    public function update()
    {
        $this->validate();

        $album = Album::findOrFail($this->album_id);

        $coverPath = $album->cover_image;
        if ($this->cover_image) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $this->cover_image->store('albums/covers', 'public');
        }

        $album->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . time(),
            'description' => $this->description,
            'cover_photo' => $coverPath,
        ]);

        session()->flash('message', 'Album berhasil diperbarui!');
        $this->resetFields();
        $this->dispatch('closeModal');
    }

    public function delete($id)
    {
        $album = Album::findOrFail($id);

        if ($album->cover_image && Storage::disk('public')->exists($album->cover_image)) {
            Storage::disk('public')->delete($album->cover_image);
        }

        $album->delete();
        session()->flash('message', 'Album berhasil dihapus!');
    }

    public function render()
    {
        $albums = Album::withCount('photos')
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(8);

        return view('livewire.admin.album-manager', compact('albums'))
            ->layout('layouts.admin');
    }
}
