<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WatermarkSetting;

class WatermarkSettingComponent extends Component
{
    use WithFileUploads;

    public $watermark_image;
    public $position;
    public $opacity;
    public $scale;
    public $current_image;

    public function mount()
    {
        $setting = WatermarkSetting::first();
        if ($setting) {
            $this->position = $setting->position ?? 'center';
            $this->opacity = $setting->opacity ?? 50;
            $this->scale = $setting->scale ?? 20;
            $this->current_image = $setting->logo_path;
        }
    }

    public function save()
    {
        $this->validate([
            'watermark_image' => 'nullable|image|max:2048',
            'position' => 'required|in:top-left,top-right,bottom-left,bottom-right,center',
            'opacity' => 'required|integer|min:1|max:100',
            'scale' => 'required|integer|min:5|max:100',
        ]);

        $setting = WatermarkSetting::first() ?? new WatermarkSetting();

        if ($this->watermark_image) {
            $path = $this->watermark_image->store('watermarks', 'public');
            $setting->logo_path = $path;
        }

        $setting->position = $this->position;
        $setting->opacity = $this->opacity;
        $setting->scale = $this->scale;
        $setting->save();

        session()->flash('message', 'Pengaturan Watermark berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.watermark-setting-component')
            ->layout('layouts.admin');
    }
}
