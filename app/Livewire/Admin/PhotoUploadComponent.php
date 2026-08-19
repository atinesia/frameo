<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Album;
use App\Models\Photo;
use App\Services\PhotoService;

class PhotoUploadComponent extends Component
{
    use WithFileUploads;

    public $album_id;
    public $photos = [];
    public $default_price = 15000;

    public function save(PhotoService $photoService)
    {
        $this->validate([
            'album_id' => 'required|exists:albums,id',
            'photos.*' => 'image|max:10240', // Maks 10MB per foto
            'default_price' => 'required|numeric|min:0',
        ]);

        foreach ($this->photos as $photoFile) {
            $data = $photoService->processAndSavePhoto($photoFile, $this->album_id, $this->default_price);
            //dd($data);
            Photo::create($data);
        }

        $this->reset(['photos']);
        session()->flash('message', 'Semua foto berhasil di-upload dan di-watermark!');
    }

    public function render()
    {
        return view('livewire.admin.photo-upload-component', [
            'albums' => Album::latest()->get()
        ])->layout('layouts.admin');
    }
}
