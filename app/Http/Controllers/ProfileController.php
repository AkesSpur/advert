<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileImage;
use App\Models\ProfileVideo;
use App\Models\Service;
use App\Models\PaidService;
use App\Models\Neighborhood;
use App\Models\MetroStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the profiles.
     */
    public function index(Request $request)
    {
        // $query = Profile::query()->with(['user', 'services', 'reviews']);

    //     // Apply filters if they exist
    //     if ($request->filled('category')) {
    //         $query->where('category', $request->category);
    //     }

    //     if ($request->filled('city')) {
    //         $query->where('city', 'like', '%' . $request->city . '%');
    //     }

    //     if ($request->filled('service_id')) {
    //         $query->whereHas('services', function ($q) use ($request) {
    //             $q->where('services.id', $request->service_id);
    //         });
    // }

    //     // Apply sorting
    //     $sortBy = $request->get('sort_by', 'created_at');
    //     $sortDirection = $request->get('sort_direction', 'desc');

    //     $allowedSorts = ['created_at', 'rating'];
    //     if (in_array($sortBy, $allowedSorts)) {
    //         if ($sortBy === 'rating') {
    //             $query->withAvg('reviews', 'rating')
    //                 ->orderBy('reviews_avg_rating', $sortDirection);
    //         } else {
    //             $query->orderBy($sortBy, $sortDirection);
    //         }
    //     }

    //     $profiles = $query->paginate(12)->withQueryString();
    //     $services = Service::all();

    $profiles = Profile::where("user_id", Auth::id())
                        ->with(['primaryImage', 'services', 'metroStations', 'neighborhoods'])
                        ->orderBY('created_at', 'desc')
                        ->get();
    
//     echo '<pre>';
//  var_dump($profiles);
//  die;

        return view('profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new profile.
     */
    public function create()
    {
        // Check if the user already has a profile
        if (Auth::user()->profile) {
            return redirect()->route('profiles.edit', Auth::user()->profile)
                ->with('warning', 'У вас уже есть профиль. Вы можете отредактировать его.');
        }

        $services = Service::all();
        $paidServices = PaidService::all();
        $neighborhoods = Neighborhood::all();
        $metroStations = MetroStation::all();
        $categories = ['Массажист', 'Фотограф', 'Визажист', 'Стилист', 'Тренер'];
        
        return view('profiles.create', compact('services', 'categories', 'neighborhoods', 'metroStations', 'paidServices'));
    }

    /**
     * Store a newly created profile in storage.
     */
    public function store(Request $request)
    {
        // echo '<pre>';
        // var_dump($request->all());
        // die;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:18|max:100',
            'weight' => 'nullable|integer|min:30|max:200',
            'height' => 'nullable|integer|min:100|max:220',
            'size' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'bio' => 'nullable|string|max:1000',
            'profile_type' => 'required|in:individual,salon',
            'hair_color' => 'nullable|string|max:50',
            'tattoo' => 'nullable|string|max:100',
            'telegram' => 'nullable|string|max:100',
            'viber' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'payment_wmz' => 'nullable|boolean',
            'payment_card' => 'nullable|boolean',
            'payment_sbp' => 'nullable|boolean',
            'has_telegram' => 'nullable|boolean',
            'has_viber' => 'nullable|boolean',
            'has_whatsapp' => 'nullable|boolean',
            'vyezd' => 'nullable|boolean',
            'appartamenti' => 'nullable|boolean',
            'vyezd_1hour' => 'nullable|numeric|min:0',
            'vyezd_2hours' => 'nullable|numeric|min:0',
            'vyezd_night' => 'nullable|numeric|min:0',
            'appartamenti_1hour' => 'nullable|numeric|min:0',
            'appartamenti_2hours' => 'nullable|numeric|min:0',
            'appartamenti_night' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'neighborhoods' => 'nullable|array',
            'neighborhoods.*' => 'exists:neighborhoods,id',
            'metro_stations' => 'nullable|array',
            'metro_stations.*' => 'exists:metro_stations,id',
            'paid_services' => 'nullable|array',
            'paid_services.*' => 'exists:paid_services,id',
            'paid_service_prices' => 'nullable|array',
            'photos' => 'required|array|min:1|max:5',
            'photos.*' => 'image|max:2048', // 2MB max
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480', // 20MB max
        ]);

        $profile = new Profile();
        $profile->user_id = Auth::id();
        $profile->name = $validated['name'];
        $profile->slug = Str::slug($validated['name']);
        $profile->age = $validated['age'] ?? null;
        $profile->weight = $validated['weight'] ?? null;
        $profile->height = $validated['height'] ?? null;
        $profile->size = $validated['size'] ?? null;
        $profile->phone = $validated['phone'];
        $profile->description = $validated['description'] ?? null;
        $profile->bio = $validated['bio'] ?? null;
        $profile->is_active = true; // Default to active

        // Set additional fields
        $profile->profile_type = $validated['profile_type'];
        $profile->hair_color = $validated['hair_color'] ?? null;
        $profile->tattoo = $validated['tattoo'] ?? null;
        $profile->telegram = $validated['telegram'] ?? null;
        $profile->viber = $validated['viber'] ?? null;
        $profile->whatsapp = $validated['whatsapp'] ?? null;
        $profile->email = $validated['email'] ?? null;
        $profile->payment_wmz = $validated['payment_wmz'] ?? false;
        $profile->payment_card = $validated['payment_card'] ?? false;
        $profile->payment_sbp = $validated['payment_sbp'] ?? false;
        $profile->has_telegram = $validated['has_telegram'] ?? false;
        $profile->has_viber = $validated['has_viber'] ?? false;
        $profile->has_whatsapp = $validated['has_whatsapp'] ?? false;
        $profile->vyezd = $validated['vyezd'] ?? false;
        $profile->appartamenti = $validated['appartamenti'] ?? false;
        $profile->vyezd_1hour = $validated['vyezd_1hour'] ?? null;
        $profile->vyezd_2hours = $validated['vyezd_2hours'] ?? null;
        $profile->vyezd_night = $validated['vyezd_night'] ?? null;
        $profile->appartamenti_1hour = $validated['appartamenti_1hour'] ?? null;
        $profile->appartamenti_2hours = $validated['appartamenti_2hours'] ?? null;
        $profile->appartamenti_night = $validated['appartamenti_night'] ?? null;

        $profile->save();

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $isPrimary = true; // First photo is primary
            $sortOrder = 0;
            
            foreach ($request->file('photos') as $photo) {
                // Store original image
                $path = $photo->store('profile-photos', 'public');
                
                // Convert to WebP for better compression if GD library is available
                try {
                    if (function_exists('imagecreatefromjpeg') && function_exists('imagewebp')) {
                        $originalPath = Storage::disk('public')->path($path);
                        $webpPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' . 
                                   pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';
                        
                        // Create image resource based on file type
                        $extension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
                        $imageResource = null;
                        
                        if ($extension === 'jpg' || $extension === 'jpeg') {
                            $imageResource = imagecreatefromjpeg($originalPath);
                        } elseif ($extension === 'png') {
                            $imageResource = imagecreatefrompng($originalPath);
                        }
                        
                        if ($imageResource) {
                            // Convert to WebP with 80% quality
                            imagewebp($imageResource, $webpPath, 80);
                            imagedestroy($imageResource);
                            
                            // Update path to use WebP version
                            $webpRelativePath = 'profile-photos/' . pathinfo($path, PATHINFO_FILENAME) . '.webp';
                            if (file_exists($webpPath)) {
                                $path = $webpRelativePath;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but continue with original image
                    Log::error('Failed to convert image to WebP: ' . $e->getMessage());
                }
                
                $profileImage = new ProfileImage([
                    'path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $sortOrder,
                ]);
                
                $profile->images()->save($profileImage);
                
                $isPrimary = false; // Only first photo is primary
                $sortOrder++;
            }
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $path = $videoFile->store('profile-videos', 'public');
            
            // Generate thumbnail from video
            $thumbnailPath = null;
            try {
                // Create a thumbnail from the first frame of the video
                $thumbnailName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME) . '_thumbnail.jpg';
                $thumbnailPath = 'profile-videos/thumbnails/' . $thumbnailName;
                
                // Ensure the thumbnails directory exists
                Storage::disk('public')->makeDirectory('profile-videos/thumbnails');
                
                // Use FFMpeg if available, otherwise fallback to simple method
                if (class_exists('\FFMpeg\FFMpeg') && class_exists('\FFMpeg\Coordinate\TimeCode')) {
                    try {
                        $ffmpeg = \FFMpeg\FFMpeg::create();
                        $video = $ffmpeg->open(Storage::disk('public')->path($path));
                        $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
                        $frame->save(Storage::disk('public')->path($thumbnailPath));
                    } catch (\Exception $e) {
                        Log::error('FFMpeg error: ' . $e->getMessage());
                        // Continue with fallback method
                    }
                } else {
                    // Fallback method - store a placeholder thumbnail
                    // In production, you would want to install FFMpeg for proper thumbnail generation
                    $placeholderPath = public_path('assets/images/video-placeholder.jpg');
                    if (file_exists($placeholderPath)) {
                        Storage::disk('public')->put($thumbnailPath, file_get_contents($placeholderPath));
                    }
                }
            } catch (\Exception $e) {
                // Log the error but continue without a thumbnail
                Log::error('Failed to create video thumbnail: ' . $e->getMessage());
            }
            
            $profileVideo = new ProfileVideo([
                'path' => $path,
                'thumbnail_path' => $thumbnailPath,
            ]);
            
            $profile->video()->save($profileVideo);
        }

        // Attach services if any
        if (isset($validated['services'])) {
            $profile->services()->sync($validated['services']);
        } else {
            $profile->services()->detach();
        }

        // Attach neighborhoods
        if (isset($validated['neighborhoods'])) {
            $profile->neighborhoods()->sync($validated['neighborhoods']);
        } else {
            $profile->neighborhoods()->detach();
        }

        // Attach metro stations
        if (isset($validated['metro_stations'])) {
            $profile->metroStations()->sync($validated['metro_stations']);
        } else {
            $profile->metroStations()->detach();
        }

        // Attach paid services with prices
        if (!empty($validated['paid_services'])) {
            $paidServiceData = [];
            
            foreach ($validated['paid_services'] as $paidServiceId) {
                $price = $validated['paid_service_prices'][$paidServiceId] ?? null;
                
                $paidServiceData[$paidServiceId] = [
                    'price' => $price,
                ];
            }
            
        $profile->has_telegram = $validated['has_telegram'] ?? false;
        $profile->has_viber = $validated['has_viber'] ?? false;
        $profile->has_whatsapp = $validated['has_whatsapp'] ?? false;
        $profile->vyezd = $validated['vyezd'] ?? false;
        $profile->appartamenti = $validated['appartamenti'] ?? false;
        $profile->vyezd_1hour = $validated['vyezd_1hour'] ?? null;
        $profile->vyezd_2hours = $validated['vyezd_2hours'] ?? null;
        $profile->vyezd_night = $validated['vyezd_night'] ?? null;
        $profile->appartamenti_1hour = $validated['appartamenti_1hour'] ?? null;
        $profile->appartamenti_2hours = $validated['appartamenti_2hours'] ?? null;
        $profile->appartamenti_night = $validated['appartamenti_night'] ?? null;

        $profile->paidServices()->sync($paidServiceData);
        } else {
            $profile->paidServices()->detach();
        }

        return redirect()->route('user.profiles.index', $profile)
            ->with('success', 'Профиль успешно создан!');
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit(Profile $profile)
    {
        // Ensure the user can only edit their own profile
        if ($profile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $services = Service::all();
        $paidServices = PaidService::all();
        $neighborhoods = Neighborhood::all();
        $metroStations = MetroStation::all();
        $categories = ['Массажист', 'Фотограф', 'Визажист', 'Стилист', 'Тренер'];
        
        // Load profile with relationships
        $profile->load(['services', 'paidServices', 'neighborhoods', 'metroStations', 'images', 'video']);
        
        return view('form.edit', compact('profile', 'services', 'categories', 'neighborhoods', 'metroStations', 'paidServices'));
    }

    /**
     * Update the specified profile in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        // Ensure the user can only update their own profile
        if ($profile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:18|max:100',
            'weight' => 'nullable|integer|min:30|max:200',
            'height' => 'nullable|integer|min:100|max:220',
            'size' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'bio' => 'nullable|string|max:1000',
            'profile_type' => 'required|in:individual,salon',
            'hair_color' => 'nullable|string|max:50',
            'tattoo' => 'nullable|string|max:100',
            'telegram' => 'nullable|string|max:100',
            'viber' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'payment_wmz' => 'nullable|boolean',
            'payment_card' => 'nullable|boolean',
            'payment_sbp' => 'nullable|boolean',
            'has_telegram' => 'nullable|boolean',
            'has_viber' => 'nullable|boolean',
            'has_whatsapp' => 'nullable|boolean',
            'vyezd' => 'nullable|boolean',
            'appartamenti' => 'nullable|boolean',
            'vyezd_1hour' => 'nullable|numeric|min:0',
            'vyezd_2hours' => 'nullable|numeric|min:0',
            'vyezd_night' => 'nullable|numeric|min:0',
            'appartamenti_1hour' => 'nullable|numeric|min:0',
            'appartamenti_2hours' => 'nullable|numeric|min:0',
            'appartamenti_night' => 'nullable|numeric|min:0',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'neighborhoods' => 'nullable|array',
            'neighborhoods.*' => 'exists:neighborhoods,id',
            'metro_stations' => 'nullable|array',
            'metro_stations.*' => 'exists:metro_stations,id',
            'paid_services' => 'nullable|array',
            'paid_services.*' => 'exists:paid_services,id',
            'paid_service_prices' => 'nullable|array',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|max:2048', // 2MB max
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480', // 20MB max
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'integer|exists:profile_images,id',
            'delete_video' => 'nullable|boolean',
        ]);

        // Update profile basic information
        $profile->name = $validated['name'];
        $profile->age = $validated['age'] ?? null;
        $profile->weight = $validated['weight'] ?? null;
        $profile->height = $validated['height'] ?? null;
        $profile->size = $validated['size'] ?? null;
        $profile->phone = $validated['phone'];
        $profile->description = $validated['description'] ?? null;
        $profile->bio = $validated['bio'] ?? null;

        // Update additional fields
        $profile->profile_type = $validated['profile_type'];
        $profile->hair_color = $validated['hair_color'] ?? null;
        $profile->tattoo = $validated['tattoo'] ?? null;
        $profile->telegram = $validated['telegram'] ?? null;
        $profile->viber = $validated['viber'] ?? null;
        $profile->whatsapp = $validated['whatsapp'] ?? null;
        $profile->email = $validated['email'] ?? null;
        $profile->payment_wmz = $validated['payment_wmz'] ?? false;
        $profile->payment_card = $validated['payment_card'] ?? false;
        $profile->payment_sbp = $validated['payment_sbp'] ?? false;
        $profile->has_telegram = $validated['has_telegram'] ?? false;
        $profile->has_viber = $validated['has_viber'] ?? false;
        $profile->has_whatsapp = $validated['has_whatsapp'] ?? false;
        $profile->vyezd = $validated['vyezd'] ?? false;
        $profile->appartamenti = $validated['appartamenti'] ?? false;
        $profile->vyezd_1hour = $validated['vyezd_1hour'] ?? null;
        $profile->vyezd_2hours = $validated['vyezd_2hours'] ?? null;
        $profile->vyezd_night = $validated['vyezd_night'] ?? null;
        $profile->appartamenti_1hour = $validated['appartamenti_1hour'] ?? null;
        $profile->appartamenti_2hours = $validated['appartamenti_2hours'] ?? null;
        $profile->appartamenti_night = $validated['appartamenti_night'] ?? null;

        $profile->save();

        // Delete photos if requested
        if (!empty($validated['delete_photos'])) {
            $photosToDelete = ProfileImage::whereIn('id', $validated['delete_photos'])
                ->where('profile_id', $profile->id)
                ->get();
            
            $hasPrimaryToDelete = $photosToDelete->where('is_primary', true)->count() > 0;
                
            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
            
            // If primary image was deleted, set a new primary image
            if ($hasPrimaryToDelete) {
                $remainingImage = $profile->images()->first();
                if ($remainingImage) {
                    $remainingImage->is_primary = true;
                    $remainingImage->save();
                }
            }
        }

        // Delete video if requested
        if (!empty($validated['delete_video']) && $profile->videos()->exists()) {
            foreach ($profile->videos as $video) {
                Storage::disk('public')->delete($video->path);
                if ($video->thumbnail_path) {
                    Storage::disk('public')->delete($video->thumbnail_path);
                }
                $video->delete();
            }
        }

        // Handle new photo uploads
        if ($request->hasFile('photos')) {
            $currentPhotoCount = $profile->images()->count();
            $allowedNewPhotos = 5 - $currentPhotoCount;
            
            if ($allowedNewPhotos > 0) {
                $photosToProcess = array_slice($request->file('photos'), 0, $allowedNewPhotos);
                $sortOrder = $profile->images()->max('sort_order') + 1;
                
                foreach ($photosToProcess as $photo) {
                    // Store original image
                    $path = $photo->store('profile-photos', 'public');
                    
                    // Convert to WebP for better compression if GD library is available
                    try {
                        if (function_exists('imagecreatefromjpeg') && function_exists('imagewebp')) {
                            $originalPath = Storage::disk('public')->path($path);
                            $webpPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' . 
                                       pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';
                            
                            // Create image resource based on file type
                            $extension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
                            $imageResource = null;
                            
                            if ($extension === 'jpg' || $extension === 'jpeg') {
                                $imageResource = imagecreatefromjpeg($originalPath);
                            } elseif ($extension === 'png') {
                                $imageResource = imagecreatefrompng($originalPath);
                            }
                            
                            if ($imageResource) {
                                // Convert to WebP with 80% quality
                                imagewebp($imageResource, $webpPath, 80);
                                imagedestroy($imageResource);
                                
                                // Update path to use WebP version
                                $webpRelativePath = 'profile-photos/' . pathinfo($path, PATHINFO_FILENAME) . '.webp';
                                if (file_exists($webpPath)) {
                                    $path = $webpRelativePath;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Log error but continue with original image
                        Log::error('Failed to convert image to WebP: ' . $e->getMessage());
                    }
                    
                    $profileImage = new ProfileImage([
                        'path' => $path,
                        'is_primary' => $currentPhotoCount === 0 && $sortOrder === 0, // Primary only if no existing photos
                        'sort_order' => $sortOrder,
                    ]);
                    
                    $profile->images()->save($profileImage);
                    $sortOrder++;
                }
            }
        }

        // Handle new video upload
        if ($request->hasFile('video') && !$profile->videos()->exists()) {
            $videoFile = $request->file('video');
            $path = $videoFile->store('profile-videos', 'public');
            
            // Generate thumbnail from video
            $thumbnailPath = null;
            try {
                // Create a thumbnail from the first frame of the video
                $thumbnailName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME) . '_thumbnail.jpg';
                $thumbnailPath = 'profile-videos/thumbnails/' . $thumbnailName;
                
                // Ensure the thumbnails directory exists
                Storage::disk('public')->makeDirectory('profile-videos/thumbnails');
                
                // Use FFMpeg if available, otherwise fallback to simple method
                if (class_exists('\FFMpeg\FFMpeg') && class_exists('\FFMpeg\Coordinate\TimeCode')) {
                    try {
                        $ffmpeg = \FFMpeg\FFMpeg::create();
                        $video = $ffmpeg->open(Storage::disk('public')->path($path));
                        $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
                        $frame->save(Storage::disk('public')->path($thumbnailPath));
                    } catch (\Exception $e) {
                        Log::error('FFMpeg error: ' . $e->getMessage());
                        // Continue with fallback method
                    }
                } else {
                    // Fallback method - store a placeholder thumbnail
                    $placeholderPath = public_path('assets/images/video-placeholder.jpg');
                    if (file_exists($placeholderPath)) {
                        Storage::disk('public')->put($thumbnailPath, file_get_contents($placeholderPath));
                    }
                }
            } catch (\Exception $e) {
                // Log the error but continue without a thumbnail
                Log::error('Failed to create video thumbnail: ' . $e->getMessage());
            }
            
            $profileVideo = new ProfileVideo([
                'path' => $path,
                'thumbnail_path' => $thumbnailPath,
            ]);
            
            $profile->video()->save($profileVideo);
        }

        // Update services
        if (isset($validated['services'])) {
            $profile->services()->sync($validated['services']);
        } else {
            $profile->services()->detach();
        }

        // Update neighborhoods
        if (isset($validated['neighborhoods'])) {
            $profile->neighborhoods()->sync($validated['neighborhoods']);
        } else {
            $profile->neighborhoods()->detach();
        }

        // Update metro stations
        if (isset($validated['metro_stations'])) {
            $profile->metroStations()->sync($validated['metro_stations']);
        } else {
            $profile->metroStations()->detach();
        }

        // Update paid services with prices
        if (!empty($validated['paid_services'])) {
            $paidServiceData = [];
            
            foreach ($validated['paid_services'] as $paidServiceId) {
                $price = $validated['paid_service_prices'][$paidServiceId] ?? null;
                
                $paidServiceData[$paidServiceId] = [
                    'price' => $price,
                ];
            }
            
            $profile->paidServices()->sync($paidServiceData);
        } else {
            $profile->paidServices()->detach();
        }

        return redirect()->route('user.profiles.index', $profile)
            ->with('success', 'Профиль успешно обновлен!');
    }

    /**
     * Remove the specified profile from storage.
     */
    public function destroy(Profile $profile)
    {
        // Ensure the user can only delete their own profile
        if ($profile->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete profile images if exist
        foreach ($profile->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        // Delete profile video if exists
        if ($profile->video) {
            Storage::disk('public')->delete($profile->video->path);
            if ($profile->video->thumbnail_path) {
                Storage::disk('public')->delete($profile->video->thumbnail_path);
            }
        }

        // Delete the profile (related records will be deleted via cascade)
        $profile->delete();

        return redirect()->route('user.profiles.index')
            ->with('success', 'Профиль успешно удален.');
    }
}
