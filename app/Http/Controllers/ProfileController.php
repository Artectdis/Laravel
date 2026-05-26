<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class ProfileController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
{
    $user = Auth::user();

    // 1. Paginate Follows
    $follows = $user->following()
        ->paginate(5, ['*'], 'follows_page')
        ->appends(request()->except('follows_page'));

    // 2. Paginate Followers
    $followers = $user->followers()
        ->paginate(5, ['*'], 'followers_page')
        ->appends(request()->except('followers_page'));
        
    // 3. Paginate Blocks
    $blocks = $user->blocks()
        ->latest()
        ->paginate(5, ['*'], 'blocks_page')
        ->appends(request()->except('blocks_page'));

    // 4. Paginate Chirps
    $chirps = $user->chirps()
        ->whereNull('parent_id')
        ->latest()
        ->paginate(5, ['*'], 'chirps_page')
        ->appends(request()->except('chirps_page'));

    // 5. Paginate Replies
    $replies = $user->chirps()
        ->whereNotNull('parent_id')
        ->latest()
        ->paginate(5, ['*'], 'replies_page')
        ->appends(request()->except('replies_page'));



        return view('profile', compact('chirps', 'replies', 'blocks', 'user', 'follows', 'followers'));
}

        
    public function showProfile(string $id)
{
    $user = User::with(['chirps'])->withCount(['followers', 'following'])->findOrFail($id);

    $chirps = $user->chirps()
        ->whereNull('parent_id')
        ->paginate(5, ['*'], 'chirps_page')
        ->appends([
            'tab' => 'chirps',
            'replies_page' => request('replies_page') // Keep the other tab's page
        ]);

    $replies = $user->chirps()
        ->whereNotNull('parent_id')
        ->paginate(5, ['*'], 'replies_page')
        ->appends([
            'tab' => 'replies',
            'chirps_page' => request('chirps_page') // Keep the other tab's page
        ]);

    $editPermission = ($user->id == Auth::id());
    return view('profileView', compact('chirps', 'replies', 'user', 'editPermission'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        if (Auth::id() !== (int)$id) { // if not the same user
            abort(403, "You cannot edit someone else's profile.");
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required','email', Rule::unique('users')->ignore($id),
            'phone_number'  => 'nullable|string',
            'birthday'      => 'required|date',
            'bio'           => 'nullable|string|min:5|max:500',
        ]);
                
        $user = User::findOrFail($id);
        $user->update($validated);
        return redirect('/settings?saved=true')->with('success', 'Profile updated successfully!');
    }

     public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ], [
            'avatar.required' => 'Please upload a profile picture.',
            'avatar.image'    => 'The file must be an actual image (jpg, png, etc.).',
            'avatar.max'      => 'The image size cannot exceed 2MB.',
        ]);
        $user = Auth::user();
        $file = $request->file('avatar');

        // optimize image
        $manager = new ImageManager(new Driver());
        $image = $manager->decode($file);
        $image->scale(height: 300);
        $encoded = $image->encodeUsingFormat(Format::WEBP, quality: 80);

        if ($user->avatar) {
            Storage::disk('supabase')->delete($user->avatar);
        }

        $filename = 'avatar_' . $user->id . '_' . time() . '.webp';

        Storage::disk('supabase')->put($filename, $encoded);

        $user->update([
            'avatar' => $filename
        ]);

        return redirect('/settings?saved=true')->with('success', 'Profile picture updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::id() !== (int)$id) {
            abort(403, "You cannot delete someone else's account.");
        }
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/')->with('success', 'Account successfully deleted.');
    }

    public function sendVerification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect('/settings')->with('status', 'profile-verified');
    }
}
