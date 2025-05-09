<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\VerificationPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    /**
     * Show the verification form for a profile
     */
    public function showVerificationForm($id)
    {
        $profile = Profile::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Get verification status if exists
        $verification = VerificationPhoto::where('profile_id', $profile->id)->first();

        return view('profiles.verification.form', compact('profile', 'verification'));
    }

    /**
     * Submit verification photo
     */
    public function submitVerification(Request $request, $id)
    {
        $request->validate([
            'verification_photo' => 'required|image|max:5120', // 5MB max
        ]);

        $profile = Profile::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if verification already exists
        $existingVerification = VerificationPhoto::where('profile_id', $profile->id)->first();
        
        if ($existingVerification) {
            // Delete old photo if exists
            if ($existingVerification->photo_path && Storage::disk('public')->exists($existingVerification->photo_path)) {
                Storage::disk('public')->delete($existingVerification->photo_path);
            }
            $verification = $existingVerification;
        } else {
            $verification = new VerificationPhoto();
            $verification->profile_id = $profile->id;
            $verification->status = 'pending';
        }

        // Store the new photo
        if ($request->hasFile('verification_photo')) {
            $path = $request->file('verification_photo')->store('verification_photos', 'public');
            $verification->photo_path = $path;
            $verification->status = 'pending'; // Reset status if resubmitting
            $verification->save();
        }

        return redirect()->route('user.profiles.index')
            ->with('success', 'Фото для верификации успешно отправлено. Ожидайте проверки администратором.');
    }
    
    /**
     * Allow users to reapply for verification after rejection
     */
    public function reapplyVerification($id)
    {
        $profile = Profile::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Check if verification exists and was rejected
        $verification = VerificationPhoto::where('profile_id', $profile->id)
            ->where('status', 'rejected')
            ->first();
            
        if (!$verification) {
            return redirect()->route('user.profiles.index')
                ->with('error', 'Невозможно повторно подать заявку на верификацию. Нет отклоненной заявки.');
        }
        
        // Redirect to verification form
        return redirect()->route('user.profiles.verification.form', $profile->id);
    }

    /**
     * Admin: List all pending verification requests
     */
    public function adminVerificationList()
    {
        $verifications = VerificationPhoto::with('profile')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.verifications.index', compact('verifications'));
    }

    /**
     * Admin: View a specific verification request
     */
    public function adminViewVerification($id)
    {
        $verification = VerificationPhoto::with('profile')->findOrFail($id);
        return view('admin.verifications.show', compact('verification'));
    }

    /**
     * Admin: Approve or reject a verification request
     */
    public function adminProcessVerification(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $verification = VerificationPhoto::findOrFail($id);
        $profile = Profile::findOrFail($verification->profile_id);

        if ($request->action === 'approve') {
            $verification->status = 'approved';
            $profile->is_verified = true;
        } else {
            $verification->status = 'rejected';
            // Don't change profile verification status if rejected
        }

        $verification->save();
        $profile->save();

        return redirect()->route('admin.verifications.index')
            ->with('success', 'Верификация успешно ' . ($request->action === 'approve' ? 'одобрена' : 'отклонена'));
    }
}