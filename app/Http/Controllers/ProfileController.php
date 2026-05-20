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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
    public function show(Chirp $chirps)
    {
        $chirps = Auth::user()->chirps()->get();
        $user = Auth::user();
        $follows = $user->following()->get(); 
        return view('profile', compact('chirps', 'user', 'follows'));
    }
        
    public function showProfile(string $id)
    {
        $user = User::withCount(['followers', 'following'])->findOrFail($id);
        $chirps = $user->chirps()->get();
        $editPermission = ($user->id == Auth::id());
        return view('profileView', compact('chirps', 'user', 'editPermission'));
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

        $data = $request->only(['name', 'email', 'phone_number', 'birthday']);
        
        // grab user and update
        $user = User::findOrFail($id);
        $user->update($data);
        return redirect('/profile?saved=true')->with('success', 'Profile updated successfully!');
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

        return redirect('/profile?saved=true')->with('success', 'Profile picture updated!');
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
}
