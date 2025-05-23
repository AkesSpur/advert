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
     * Archive the specified profile.
     */
    public function archive($id)
    {
        $profile = Profile::where("user_id", Auth::id())->findOrFail($id);
        
        // Only inactive profiles can be archived
        if ($profile->is_active) {
            return redirect()->route('user.profiles.index')
                ->with('error', 'Только неактивные анкеты могут быть архивированы.');
        }        
        
        $profile->is_archived = true;
        $profile->save();
        
        
        return redirect()->route('user.profiles.index')
            ->with('success', 'Анкета успешно архивирована.');
    }
    
    /**
     * Restore the archived profile.
     */
    public function restore($id)
    {
        $profile = Profile::where("user_id", Auth::id())
            ->where("is_archived", true)
            ->findOrFail($id);
            
        $profile->is_archived = false;
        $profile->save();
        
        return redirect()->route('user.profiles.index')
            ->with('success', 'Анкета успешно восстановлена.');
    }
    /**
     * Create a fallback thumbnail when FFMpeg is not available or fails
     * 
     * @param string $thumbnailPath The path where the thumbnail should be stored
     * @return string The thumbnail path if successful, null otherwise
     */
    private function createFallbackThumbnail($thumbnailPath)
    {
        try {
            // Try to use a placeholder image
            $placeholderPath = public_path('assets/images/video-placeholder.jpg');
            
            // If placeholder exists, use it
            if (file_exists($placeholderPath)) {
                // Ensure the directory exists
                $fullThumbnailPath = Storage::disk('public')->path($thumbnailPath);
                $thumbnailDir = dirname($fullThumbnailPath);
                
                if (!file_exists($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }
                
                // Copy the placeholder to the thumbnail location
                if (Storage::disk('public')->put($thumbnailPath, file_get_contents($placeholderPath))) {
                    return $thumbnailPath;
                }
            } else {
                // Create a simple black image if no placeholder is available
                $fullThumbnailPath = Storage::disk('public')->path($thumbnailPath);
                $thumbnailDir = dirname($fullThumbnailPath);
                
                if (!file_exists($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }
                
                // Create a simple black image
                if (function_exists('imagecreatetruecolor')) {
                    $image = imagecreatetruecolor(320, 240);
                    $bgColor = imagecolorallocate($image, 0, 0, 0);
                    imagefill($image, 0, 0, $bgColor);
                    
                    // Add text "Video" in white
                    $textColor = imagecolorallocate($image, 255, 255, 255);
                    imagestring($image, 5, 120, 110, 'Video', $textColor);
                    
                    // Save as JPEG
                    imagejpeg($image, $fullThumbnailPath, 90);
                    imagedestroy($image);
                    
                    return $thumbnailPath;
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to create fallback thumbnail: ' . $e->getMessage());
        }
        
        return null;
    }
    /**
     * Display a listing of the profiles.
     */
    public function index(Request $request)
    {
        $profiles = Profile::where("user_id", Auth::id())
            ->where("is_archived", false)
            ->with(['primaryImage', 'services', 'metroStations', 'neighborhoods', 'tariffs'])
            ->withCount('tariffs')
            ->orderBY('created_at', 'desc')
            ->get();
            
        $archivedProfiles = Profile::where("user_id", Auth::id())
            ->where("is_archived", true)
            ->with(['primaryImage', 'services', 'metroStations', 'neighborhoods','tariffs'])
            ->withCount('tariffs')
            ->orderBY('created_at', 'desc')
            ->get();
            
        $archivedCount = $archivedProfiles->count();

        return view('profiles.index', compact('profiles', 'archivedProfiles', 'archivedCount'));
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
            'photos' => 'required|array|min:1|max:30',
            'photos.*' => 'image|max:5120', // 5MB max
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480', // 20MB max
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
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

        // Set additional fields
        $profile->profile_type = $validated['profile_type'];
        $profile->hair_color = $validated['hair_color'] ?? null;
        $profile->tattoo = $validated['tattoo'] ?? null;
        $profile->telegram = $validated['telegram'] ?? null;
        $profile->viber = $validated['viber'] ?? null;
        $profile->whatsapp = $validated['whatsapp'] ?? null;
        $profile->email = $validated['email'] ?? null;
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
        $profile->latitude = $validated['latitude'] ?? null;
        $profile->longitude = $validated['longitude'] ?? null;

        $profile->save();

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $isPrimary = true; // First photo is primary
            $sortOrder = 0;

            foreach ($request->file('photos') as $photo) {
                // Store image temporarily
                $tempPath = $photo->store('temp-photos', 'public');
                $originalPath = Storage::disk('public')->path($tempPath);

                // Convert to WebP for better compression
                try {
                    if (function_exists('imagecreatefromjpeg') && function_exists('imagewebp')) {
                        // Create a unique filename for the WebP version
                        $webpFilename = Str::uuid() . '.webp';
                        $webpPath = 'profile-photos/' . $webpFilename;
                        $webpFullPath = Storage::disk('public')->path($webpPath);

                        // Create image resource based on file type
                        $extension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
                        $imageResource = null;

                        if ($extension == 'jpg' || $extension == 'jpeg') {
                            $imageResource = imagecreatefromjpeg($originalPath);
                        } elseif ($extension == 'png') {
                            $imageResource = imagecreatefrompng($originalPath);
                        } elseif ($extension == 'gif') {
                            $imageResource = imagecreatefromgif($originalPath);
                        } elseif ($extension == 'webp') {
                            $imageResource = imagecreatefromwebp($originalPath);
                        }

                        if ($imageResource) {
                            // Ensure the profile-photos directory exists
                            Storage::disk('public')->makeDirectory('profile-photos');

                            // Convert to WebP with 80% quality
                            imagewebp($imageResource, $webpFullPath, 80);
                            imagedestroy($imageResource);

                            // Set path to the WebP version
                            $path = $webpPath;
                        } else {
                            // If conversion fails, use the original file
                            $path = $photo->store('profile-photos', 'public');
                        }
                    } else {
                        // If WebP conversion is not available, use the original file
                        $path = $photo->store('profile-photos', 'public');
                    }
                } catch (\Exception $e) {
                    // Log error and use the original image
                    Log::error('Failed to convert image to WebP: ' . $e->getMessage());
                    $path = $photo->store('profile-photos', 'public');
                }

                // Delete the temporary file
                Storage::disk('public')->delete($tempPath);

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

            // // Generate thumbnail from video
            // $thumbnailPath = null;
            // try {
            //     // Create a thumbnail from the first frame of the video
            //     $thumbnailName = Str::uuid() . '_thumbnail.jpg';
            //     $thumbnailPath = 'profile-videos/thumbnails/' . $thumbnailName;
            //     $fullThumbnailPath = Storage::disk('public')->path($thumbnailPath);

            //     // Ensure the thumbnails directory exists
            //     Storage::disk('public')->makeDirectory('profile-videos/thumbnails');

            //     // Use FFMpeg if available, otherwise fallback to simple method
            //     if (class_exists('\FFMpeg\FFMpeg') && class_exists('\FFMpeg\Coordinate\TimeCode')) {
            //         try {
            //             $ffmpeg = \FFMpeg\FFMpeg::create();
            //             $video = $ffmpeg->open(Storage::disk('public')->path($path));
            //             $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
                        
            //             // Ensure the directory exists before saving
            //             $thumbnailDir = dirname($fullThumbnailPath);
            //             if (!file_exists($thumbnailDir)) {
            //                 mkdir($thumbnailDir, 0755, true);
            //             }
                        
            //             // Save the thumbnail
            //             $frame->save($fullThumbnailPath);
                        
            //             // Verify the thumbnail was created
            //             if (!file_exists($fullThumbnailPath)) {
            //                 Log::error('Thumbnail file was not created at: ' . $fullThumbnailPath);
            //                 throw new \Exception('Failed to create thumbnail file');
            //             }
            //         } catch (\Exception $e) {
            //             Log::error('FFMpeg error: ' . $e->getMessage());
            //             // Continue with fallback method
            //             $thumbnailPath = $this->createFallbackThumbnail($thumbnailPath);
            //         }
            //     } else {
            //         // Fallback method - store a placeholder thumbnail
            //         $thumbnailPath = $this->createFallbackThumbnail($thumbnailPath);
            //     }
            // } catch (\Exception $e) {
            //     // Log the error but continue without a thumbnail
            //     Log::error('Failed to create video thumbnail: ' . $e->getMessage());
            // }

            $profileVideo = new ProfileVideo([
                'path' => $path,
                'thumbnail_path' => '$thumbnailPath',
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

        return redirect()->route('user.profiles.index')
            ->with('success', 'Профиль успешно создан!');
    }

    /**
     * Show the form for editing the specified profile.
     */
    public function edit(Profile $profile)
    {
        // echo Auth::user()->isAdmin();
        // die;
        // Ensure the user can only edit their own profile
        if ($profile->user_id != Auth::id() && !Auth::user()->isAdmin()) {
          
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
        //    echo '<pre>';
        // var_dump($request->all());
        // die;
        // Ensure the user can only update their own profile
        if ($profile->user_id != Auth::id() && !Auth::user()->isAdmin() ) {
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
            'photos' => 'nullable|array|max:30',
            'photos.*' => 'image|max:5120', // 5MB max
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480', // 20MB max
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'integer|exists:profile_images,id',
            'delete_video' => 'nullable|boolean',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Update profile basic information
        $profile->name = $validated['name'];
        $profile->slug = Str::slug($validated['name']);
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
        $profile->latitude = $validated['latitude'] ?? null;
        $profile->longitude = $validated['longitude'] ?? null;

        $profile->save();

        // Process new photo uploads first
        $newPhotosUploaded = false;
        if ($request->hasFile('photos')) {
            $newPhotosUploaded = true;
            $currentPhotoCount = $profile->images()->count();
            $allowedNewPhotos = 50 - $currentPhotoCount;

            if ($allowedNewPhotos > 0) {
                $photosToProcess = array_slice($request->file('photos'), 0, $allowedNewPhotos);
                $sortOrder = $profile->images()->max('sort_order') + 1;

                foreach ($photosToProcess as $photo) {
                    // Store image temporarily
                    $tempPath = $photo->store('temp-photos', 'public');
                    $originalPath = Storage::disk('public')->path($tempPath);

                    // Convert to WebP for better compression
                    try {
                        if (function_exists('imagecreatefromjpeg') && function_exists('imagewebp')) {
                            // Create a unique filename for the WebP version
                            $webpFilename = Str::uuid() . '.webp';
                            $webpPath = 'profile-photos/' . $webpFilename;
                            $webpFullPath = Storage::disk('public')->path($webpPath);

                            // Create image resource based on file type
                            $extension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));
                            $imageResource = null;

                            if ($extension == 'jpg' || $extension == 'jpeg') {
                                $imageResource = imagecreatefromjpeg($originalPath);
                            } elseif ($extension == 'png') {
                                $imageResource = imagecreatefrompng($originalPath);
                            } elseif ($extension == 'gif') {
                                $imageResource = imagecreatefromgif($originalPath);
                            } elseif ($extension == 'webp') {
                                $imageResource = imagecreatefromwebp($originalPath);
                            }

                            if ($imageResource) {
                                // Ensure the profile-photos directory exists
                                Storage::disk('public')->makeDirectory('profile-photos');

                                // Convert to WebP with 80% quality
                                imagewebp($imageResource, $webpFullPath, 80);
                                imagedestroy($imageResource);

                                // Set path to the WebP version
                                $path = $webpPath;
                            } else {
                                // If conversion fails, use the original file
                                $path = $photo->store('profile-photos', 'public');
                            }
                        } else {
                            // If WebP conversion is not available, use the original file
                            $path = $photo->store('profile-photos', 'public');
                        }
                    } catch (\Exception $e) {
                        // Log error and use the original image
                        Log::error('Failed to convert image to WebP: ' . $e->getMessage());
                        $path = $photo->store('profile-photos', 'public');
                    }

                    // Delete the temporary file
                    Storage::disk('public')->delete($tempPath);

                    // Check if this is the first image for the profile
                    $isPrimary = $profile->images()->count() == 0;

                    $profileImage = new ProfileImage([
                        'path' => $path,
                        'is_primary' => $isPrimary,
                        'sort_order' => $sortOrder,
                    ]);

                    $profile->images()->save($profileImage);
                    $sortOrder++;
                }
            }
        }

        // Process new video upload first
        $newVideoUploaded = false;
        if ($request->hasFile('video')) {
            $newVideoUploaded = true;
            $videoFile = $request->file('video');
            $path = $videoFile->store('profile-videos', 'public');
            
            // // Generate thumbnail from video
            // $thumbnailPath = null;
            // try {
            //     // Create a thumbnail from the first frame of the video
            //     $thumbnailName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME) . '_thumbnail.jpg';
            //     $thumbnailPath = 'profile-videos/thumbnails/' . $thumbnailName;
                
            //     // Ensure the thumbnails directory exists
            //     Storage::disk('public')->makeDirectory('profile-videos/thumbnails');
                
            //     // Use FFMpeg if available, otherwise fallback to simple method
            //     if (class_exists('\FFMpeg\FFMpeg') && class_exists('\FFMpeg\Coordinate\TimeCode')) {
            //         try {
            //             $ffmpeg = \FFMpeg\FFMpeg::create();
            //             $video = $ffmpeg->open(Storage::disk('public')->path($path));
            //             $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
            //             $frame->save(Storage::disk('public')->path($thumbnailPath));
            //         } catch (\Exception $e) {
            //             Log::error('FFMpeg error: ' . $e->getMessage());
            //             // Continue with fallback method
            //         }
            //     } else {
            //         // Fallback method - store a placeholder thumbnail
            //         $placeholderPath = public_path('assets/images/video-placeholder.jpg');
            //         if (file_exists($placeholderPath)) {
            //             Storage::disk('public')->put($thumbnailPath, file_get_contents($placeholderPath));
            //         }
            //     }
            // } catch (\Exception $e) {
            //     // Log the error but continue without a thumbnail
            //     Log::error('Failed to create video thumbnail: ' . $e->getMessage());
            // }
            
            // Delete existing video if there is one
            if ($profile->video) {
                Storage::disk('public')->delete($profile->video->path);
                // if ($profile->video->thumbnail_path) {
                //     Storage::disk('public')->delete($profile->video->thumbnail_path);
                // }
                $profile->video->delete();
            }
            
            $profileVideo = new ProfileVideo([
                'path' => $path,
                'thumbnail_path' => 'thumbnailPath',
            ]);
            
            $profile->video()->save($profileVideo);
        }

        // Delete photos if requested
        if (!empty($validated['delete_photos'])) {
            $photosToDelete = ProfileImage::whereIn('id', $validated['delete_photos'])
                ->where('profile_id', $profile->id)
                ->get();

            $hasPrimaryToDelete = $photosToDelete->where('is_primary', true)->count() > 0;
            $totalPhotos = $profile->images()->count();
            $totalPhotosToDelete = $photosToDelete->count();

            // Check if user is trying to delete the only primary photo without uploading a new one
            if ($hasPrimaryToDelete && $totalPhotos == $totalPhotosToDelete && !$newPhotosUploaded) {
                return redirect()->back()
                    ->with('error', 'Невозможно удалить единственное фото профиля. Пожалуйста, загрузите новое фото перед удалением.')
                    ->withInput();
            }

            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->path);

                // If this is the original file (not WebP), also delete it
                $originalPath = str_replace('.webp', '', $photo->path);
                $originalExtensions = ['.jpg', '.jpeg', '.png', '.gif'];

                foreach ($originalExtensions as $ext) {
                    $possibleOriginal = $originalPath . $ext;
                    if (Storage::disk('public')->exists($possibleOriginal)) {
                        Storage::disk('public')->delete($possibleOriginal);
                    }
                }

                $photo->delete();
            }

            // If primary image was deleted, set a new primary image
            if ($hasPrimaryToDelete) {
                // Get the first remaining image and set it as primary
                $remainingImage = $profile->images()->first();
                if ($remainingImage) {
                    $remainingImage->is_primary = true;
                    $remainingImage->save();
                }
            }

            // Always ensure there's a primary image if any images exist
            $hasPrimary = $profile->images()->where('is_primary', true)->exists();
            if (!$hasPrimary && $profile->images()->count() > 0) {
                $firstImage = $profile->images()->first();
                $firstImage->is_primary = true;
                $firstImage->save();
            }
        }

        // Delete video if requested and no new video was uploaded
        if (!empty($validated['delete_video']) && $profile->video()->exists() && !$newVideoUploaded) {
            $video = $profile->video;

            if ($video) {
                Storage::disk('public')->delete($video->path);
                if ($video->thumbnail_path) {
                    Storage::disk('public')->delete($video->thumbnail_path);
                }
                $video->delete();
            }
        }

        // Photo uploads are now handled before deletion logic
        // No need to process photos again here

        // Handle new video upload
        if ($request->hasFile('video') && !$profile->video()->exists()) {
            $videoFile = $request->file('video');
            $path = $videoFile->store('profile-videos', 'public');

            // Generate thumbnail from video
            // $thumbnailPath = null;
            // try {
            //     // Create a thumbnail from the first frame of the video
            //     $thumbnailName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME) . '_thumbnail.jpg';
            //     $thumbnailPath = 'profile-videos/thumbnails/' . $thumbnailName;

            //     // Ensure the thumbnails directory exists
            //     Storage::disk('public')->makeDirectory('profile-videos/thumbnails');

            //     // Use FFMpeg if available, otherwise fallback to simple method
            //     if (class_exists('\FFMpeg\FFMpeg') && class_exists('\FFMpeg\Coordinate\TimeCode')) {
            //         try {
            //             $ffmpeg = \FFMpeg\FFMpeg::create();
            //             $video = $ffmpeg->open(Storage::disk('public')->path($path));
            //             $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1));
            //             $frame->save(Storage::disk('public')->path($thumbnailPath));
            //         } catch (\Exception $e) {
            //             Log::error('FFMpeg error: ' . $e->getMessage());
            //             // Continue with fallback method
            //         }
            //     } else {
            //         // Fallback method - store a placeholder thumbnail
            //         $placeholderPath = public_path('assets/images/video-placeholder.jpg');
            //         if (file_exists($placeholderPath)) {
            //             Storage::disk('public')->put($thumbnailPath, file_get_contents($placeholderPath));
            //         }
            //     }
            // } catch (\Exception $e) {
            //     // Log the error but continue without a thumbnail
            //     Log::error('Failed to create video thumbnail: ' . $e->getMessage());
            // }

            $profileVideo = new ProfileVideo([
                'path' => $path,
                'thumbnail_path' => 'thumbnailPath',
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

        if (Auth::user()->isAdmin() && $profile->user_id != Auth::id())
        {
            toastr()->success( 'Профиль успешно обновлен!');
            return redirect()->route('admin.profiles.index');
        }

        return redirect()->route('user.profiles.index')
            ->with('success', 'Профиль успешно обновлен!');
    }

    /**
     * Remove the specified profile from storage.
     */
    public function destroy(Profile $profile)
    {
        // Ensure the user can only delete their own profile
        if ($profile->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete profile images if exist
        foreach ($profile->images as $image) {
            Storage::disk('public')->delete($image->path);

            // If this is the WebP file, also delete any possible original files
            $originalPath = str_replace('.webp', '', $image->path);
            $originalExtensions = ['.jpg', '.jpeg', '.png', '.gif'];

            foreach ($originalExtensions as $ext) {
                $possibleOriginal = $originalPath . $ext;
                if (Storage::disk('public')->exists($possibleOriginal)) {
                    Storage::disk('public')->delete($possibleOriginal);
                }
            }
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
