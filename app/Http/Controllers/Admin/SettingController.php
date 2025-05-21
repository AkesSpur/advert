<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\EmailSetting; // Assuming EmailSetting model exists
use App\Models\HeroSectionSetting;
use App\Models\HeroSectionOverride;
use App\Models\CustomCategory;
use App\Models\Service;
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
        $webmoneySettings = [
            'merchant_purse' => config('services.webmoney.merchant_purse'),
            'secret_key' => config('services.webmoney.secret_key'),
            'sim_mode' => config('services.webmoney.sim_mode'),
        ];
        return view('admin.setting.index', compact('generalSettings', 'emailSettings', 'logoSetting', 'webmoneySettings'));
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

    public function heroSectionOverrideIndex(Request $request)
    {
        $modelType = $request->query('model_type');
        $modelId = $request->query('model_id');
        $model = null;
        $heroOverride = null;

        if ($modelType && $modelId) {
            $modelClass = "App\\Models\\".ucfirst($modelType);
            if (class_exists($modelClass)) {
                $model = $modelClass::find($modelId);
                if ($model) {
                    $heroOverride = HeroSectionOverride::firstOrCreate(
                        ['model_type' => get_class($model), 'model_id' => $model->id]
                    );
                }
            }
        }

        // Fetch all custom categories and services for the dropdowns
        $customCategories = CustomCategory::all();
        $services = Service::all();

        return view('admin.setting.hero-section-override', compact('heroOverride', 'model', 'modelType', 'modelId', 'customCategories', 'services'));
    }

    public function updateHeroSectionOverride(Request $request)
    {
        $request->validate([
            'model_type' => ['required', 'string'],
            'model_id' => ['required', 'integer'],
            'title' => ['nullable', 'string', 'max:255'],
            'text_content' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5000'], // Max 5MB
            'is_active' => ['sometimes', 'boolean']
        ]);

        $modelClass = "App\\Models\\".ucfirst($request->model_type);
        if (!class_exists($modelClass)) {
            toastr()->error('Invalid model type provided.');
            return redirect()->back();
        }

        $model = $modelClass::find($request->model_id);
        if (!$model) {
            toastr()->error('Model not found.');
            return redirect()->back();
        }

        $heroOverride = HeroSectionOverride::firstOrNew(
            ['model_type' => get_class($model), 'model_id' => $model->id]
        );

        $imagePath = $this->updateImage($request, 'image', $heroOverride->image, 'uploads/hero_overrides');

        $heroOverride->title = $request->title;
        $heroOverride->text_content = $request->text_content;
        $heroOverride->is_active = $request->has('is_active');

        if ($imagePath) {
            $heroOverride->image = $imagePath;
        }

        $heroOverride->save();

        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        toastr()->success('Hero section override updated successfully!');
        return redirect()->route('admin.hero-section-override.index', ['model_type' => $request->model_type, 'model_id' => $request->model_id]);
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

    public function updateWebmoneySetting(Request $request)
    {
        $request->validate([
            'webmoney_merchant_purse' => ['required', 'string', 'max:255'],
            'webmoney_secret_key' => ['required', 'string', 'max:255'],
            'webmoney_sim_mode' => ['required', 'in:0,1'],
        ]);

        // It's generally better to store sensitive keys in .env file
        // and update them using a method that modifies .env or by instructing the user.
        // For simplicity here, we'll update config/services.php directly.
        // This requires config/services.php to be writable by the web server.
        // A more robust solution would involve a custom config writer or .env manipulation.

        $path = config_path('services.php');
        $config = require $path;

        // Ensure 'webmoney' key exists
        if (!isset($config['webmoney'])) {
            $config['webmoney'] = [];
        }

        $config['webmoney']['merchant_purse'] = $request->webmoney_merchant_purse;
        $config['webmoney']['secret_key'] = $request->webmoney_secret_key;
        $config['webmoney']['sim_mode'] = $request->webmoney_sim_mode;

        $newConfigContent = "<?php\n\nreturn " . var_export($config, true) . ";\n";

        file_put_contents($path, $newConfigContent);

        // Clear cache for settings
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        toastr()->success('Настройки WebMoney успешно обновлены!');
        return redirect()->back();
    }
}