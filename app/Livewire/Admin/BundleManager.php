<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Album;
use App\Models\Bundle;

class BundleManager extends Component
{
    public ?int $album_id = null;
    public ?string $name = null;
    public ?float $price = null;
    public bool $is_active = true;

    protected $rules = [
        'album_id' => 'required|exists:albums,id|unique:bundles,album_id',
        'name'     => 'required|string|max:255',
        'price'    => 'required|numeric|min:0',
    ];

    public function store()
    {
        $this->validate();

        // Hitung jumlah foto dari album yang dipilih
        $album = Album::withCount('photos')->findOrFail($this->album_id);

        Bundle::create([
            'album_id'  => $this->album_id,
            'name'      => $this->name,
            'price'     => $this->price,
            'photo_count' => $album->photos_count,
            'is_active' => $this->is_active,
        ]);

        $this->reset(['album_id', 'name', 'price']);
        session()->flash('message', 'Paket Bundle berhasil dibuat!');
    }

    public function toggleStatus($id)
    {
        $bundle = Bundle::findOrFail($id);
        $bundle->update(['is_active' => !$bundle->is_active]);
    }

    public function delete($id)
    {
        Bundle::findOrFail($id)->delete();
        session()->flash('message', 'Paket Bundle dihapus.');
    }

    public function render()
    {
        $bundles = Bundle::with('album.photos')->latest()->get();
        $albums = Album::whereDoesntHave('bundle')->has('photos')->get();

        return view('livewire.admin.bundle-manager', compact('bundles', 'albums'))
            ->layout('layouts.admin');
    }
}
