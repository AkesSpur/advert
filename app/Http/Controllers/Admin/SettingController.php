<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\EmailSetting; // Assuming EmailSetting model exists
use App\Models\HeroSectionSetting;
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
            'yandex_api_key' => ['nullable', 'string', 'max:255'], // Added Yandex API Key validation
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
                'yandex_api_key' => $request->yandex_api_key, // Added Yandex API Key saving
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
            'login_image' => ['nullable', 'image', 'max:5000'] // Max 5MB
        ]);

        $logoSetting = LogoSetting::firstOrCreate(['id' => 1]);

        $logoPath = $this->updateImage($request, 'logo', $logoSetting->logo, 'uploads/logos');
        $faviconPath = $this->updateImage($request, 'favicon', $logoSetting->favicon, 'uploads/logos');
        $loginImagePath = $this->updateImage($request, 'login_image', $logoSetting->login_image, 'uploads/logos');

        $updateData = [];
        if ($logoPath) {
            $updateData['logo'] = $logoPath;
        }
        if ($faviconPath) {
            $updateData['favicon'] = $faviconPath;
        }
        if ($loginImagePath) {
            $updateData['login_image'] = $loginImagePath;
        }

        if (!empty($updateData)) {
            $logoSetting->update($updateData);
        }
        
        // Clear cache for settings
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        toastr()->success('Настройки логотипа успешно обновлены!');
        return redirect()->back();
    }

    public function heroSectionSetting()
    {
        $heroSetting = HeroSectionSetting::first();
        return view('admin.setting.hero-section-setting', compact('heroSetting'));
    }

    public function updateHeroSectionSetting(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'text_content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5000'] // Max 5MB
        ]);

        $heroSetting = HeroSectionSetting::firstOrCreate(['id' => 1]);

        $imagePath = $this->updateImage($request, 'image', $heroSetting->image, 'uploads/hero');

        $updateData = [
            'title' => $request->title,
            'text_content' => $request->text_content,
        ];

        if ($imagePath) {
            $updateData['image'] = $imagePath;
        }

        $heroSetting->update($updateData);

        // Clear cache for settings
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        toastr()->success('Настройки секции Hero успешно обновлены!');
        return redirect()->back();
    }
}