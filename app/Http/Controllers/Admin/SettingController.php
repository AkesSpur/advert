<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\EmailSetting; // Assuming EmailSetting model exists
use App\Models\LogoSetting;  // Assuming LogoSetting model exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Traits\FileUploadTrait; // Assuming this trait is used for logo uploads

class SettingController extends Controller
{
    use FileUploadTrait; // If logo uploads involve this trait

    public function index()
    {
        $generalSettings = GeneralSetting::first();
        $emailSettings = EmailSetting::first(); // Fetch email settings
        $logoSetting = LogoSetting::first(); // Fetch logo settings
        return view('admin.setting.index', compact('generalSettings', 'emailSettings', 'logoSetting'));
    }

    public function updateGeneralSetting(Request $request)
    {
        $request->validate([
            'site_name' => ['required', 'max:200'],
            'cheap_threshold' => ['nullable', 'integer', 'min:0'],
            'profiles_per_page' => ['nullable', 'integer', 'min:1'],
            'default_h1_heading' => ['nullable', 'string', 'max:255'],
            'default_seo_title' => ['nullable', 'string', 'max:255'],
            'default_seo_description' => ['nullable', 'string'],
        ]);

        GeneralSetting::updateOrCreate(
            ['id' => 1], // Assuming there's only one row for general settings
            [
                'site_name' => $request->site_name,
                'cheap_threshold' => $request->cheap_threshold,
                'profiles_per_page' => $request->profiles_per_page,
                'default_h1_heading' => $request->default_h1_heading,
                'default_seo_title' => $request->default_seo_title,
                'default_seo_description' => $request->default_seo_description,
            ]
        );

        // Clear cache for settings
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        toastr()->success('Настройки обновлены успешно!');
        return redirect()->back();
    }

    public function updateEmailSetting(Request $request)
    {
        // Placeholder for email setting update logic
        // You'll need to implement this based on your EmailSetting model and form fields
        $request->validate([
            'email' => ['required', 'email'],
            'host' => ['required'],
            'username' => ['required'],
            'password' => ['required'],
            'port' => ['required', 'integer'],
            'encryption' => ['required', 'in:tls,ssl'],
        ]);

        EmailSetting::updateOrCreate(
            ['id' => 1], // Assuming one row for email settings
            [
                'email' => $request->email,
                'host' => $request->host,
                'username' => $request->username,
                'password' => $request->password,
                'port' => $request->port,
                'encryption' => $request->encryption,
            ]
        );
        
        // Clear cache for settings
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        toastr()->success('Настройки электронной почты обновлены успешно!');
        return redirect()->back();
    }

    public function updateLogoSetting(Request $request)
    {
        // Placeholder for logo setting update logic
        // You'll need to implement this based on your LogoSetting model and FileUploadTrait
        $request->validate([
            'logo' => ['nullable', 'image', 'max:3000'], // Max 3MB
            'favicon' => ['nullable', 'image', 'max:3000'], // Max 1MB, maybe .ico specific validation
        ]);

        $logoSetting = LogoSetting::firstOrCreate(['id' => 1]);

        $logoPath = $this->updateImage($request, 'logo', $logoSetting->logo, 'uploads/logos');
        $faviconPath = $this->updateImage($request, 'favicon', $logoSetting->favicon, 'uploads/logos');

        $updateData = [];
        if ($logoPath) {
            $updateData['logo'] = $logoPath;
        }
        if ($faviconPath) {
            $updateData['favicon'] = $faviconPath;
        }

        if (!empty($updateData)) {
            $logoSetting->update($updateData);
        }
        
        // Clear cache for settings
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        toastr()->success('Logo Settings Updated Successfully!');
        return redirect()->back();
    }
}