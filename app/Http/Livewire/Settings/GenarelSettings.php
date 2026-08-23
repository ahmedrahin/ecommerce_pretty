<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use App\Http\Livewire\Settings\Artisan;

class GenarelSettings extends Component
{
    use WithFileUploads;

    public $setting;
    public $logo, $fav_icon, $website_logo, $website_footer_logo, $dark_logo;
    public $title, $email, $number1, $number2, $state, $address;
    public $current_image, $current_favIcon, $current_websiteLogo, $current_footerLogo, $current_darkLogo;

    public function mount()
    {
        $this->setting = Setting::find(1);

        $this->title =  $this->setting->site_title ?? '';
        $this->email =  $this->setting->email ?? '';
        $this->number1 =  $this->setting->number1 ?? '';
        $this->number2 =  $this->setting->number2 ?? '';
        $this->state =   $this->setting->state ?? '';
        $this->address = $this->setting->address ?? '';
        $this->current_image = $this->setting->logo ?? '';
        $this->current_favIcon = $this->setting->fav_icon ?? '';
        $this->current_websiteLogo = $this->setting->website_logo ?? '';
        $this->current_footerLogo = $this->setting->website_footer_logo ?? '';
        $this->current_darkLogo = $this->setting->dark_logo ?? '';
    }

    protected $rules = [
        'title' => 'required',
        'email' => 'required|email',
    ];

    public function update($id = 1)
    {
        $this->validate();
    
        $updateData = Setting::find($id) ?? new Setting();
    
        // Update or create data
        $updateData->site_title = $this->title;
        $updateData->email      = $this->email;
        $updateData->number1    = $this->number1;
        $updateData->number2    = $this->number2;
        $updateData->state      = $this->state;
        $updateData->address    = $this->address;
        
        $updateData->save();
    
        // Handle images if necessary
        $this->handleImages($updateData);

        $this->updateEnvFile('APP_NAME', $this->title);
    
        // Emit success message
        $this->emit('success', __('Store settings updated successfully.'));
    }
    
    private function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            // Update existing key or add if not exists
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $envContent);
            } else {
                $envContent .= "\n{$key}=\"{$value}\"";
            }

            File::put($envPath, $envContent);
        }
    }

    public function handleImages($updateData)
    {
        // Handle individual image uploads
        $this->uploadImage($updateData, 'logo', $this->logo, $this->current_image);
        $this->uploadImage($updateData, 'fav_icon', $this->fav_icon, $this->current_favIcon);
        $this->uploadImage($updateData, 'website_logo', $this->website_logo, $this->current_websiteLogo);
        $this->uploadImage($updateData, 'website_footer_logo', $this->website_footer_logo, $this->current_footerLogo);
        $this->uploadImage($updateData, 'dark_logo', $this->dark_logo, $this->current_darkLogo);
    }

    private function uploadImage($updateData, $fieldName, $newFile, &$currentFile)
    {
        if ($newFile) {
            // Check and delete existing file
            if ($currentFile && Storage::disk('real_public')->exists($currentFile)) {
                Storage::disk('real_public')->delete($currentFile);
            }

            // Store the new file
            $imageName = time() . '_' . $newFile->getClientOriginalName();
            $path = $newFile->storeAs('uploads/logo_fav', $imageName, 'real_public');

            // Update the database
            $updateData->update([$fieldName => 'uploads/logo_fav/' . $imageName]);

            // Update the current file reference
            $currentFile = 'uploads/logo_fav/' . $imageName;
        } elseif ($currentFile === null && $updateData->$fieldName) {
            // Delete if the current file is null
            if (Storage::disk('real_public')->exists($updateData->$fieldName)) {
                Storage::disk('real_public')->delete($updateData->$fieldName);
            }
            $updateData->update([$fieldName => null]);
        }
    }

    public function removeLogo()
    {
        $this->removeFile('logo', $this->current_image);
    }

    public function removeFavIcon()
    {
        $this->removeFile('fav_icon', $this->current_favIcon);
    }

    public function removeWebsiteLogo()
    {
        $this->removeFile('website_logo', $this->current_websiteLogo);
    }

    public function removeFooterLogo()
    {
        $this->removeFile('website_footer_logo', $this->current_footerLogo);
    }

    public function removeDarkLogo()
    {
        $this->removeFile('dark_logo', $this->current_darkLogo);
    }

    private function removeFile($fieldName, &$currentFile)
    {
        if ($currentFile && Storage::disk('real_public')->exists($currentFile)) {
            Storage::disk('real_public')->delete($currentFile);
        }
        $currentFile = null;
    }

    public function render()
    {
        return view('livewire.settings.genarel-settings', [
            'setting' => $this->setting,
        ]);
    }
}
