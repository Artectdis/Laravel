<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChirpController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $oldMessage = old('message', '');
        $oldMessageLength = mb_strlen(trim(strip_tags(str_replace('&nbsp;', ' ', $oldMessage))));

    return view('home', [
        'availableTags' => Tag::orderBy('name')->get(),
        'oldMessageLength' => $oldMessageLength, // Just add it here
    ]);
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
    $plainText = trim(strip_tags(str_replace('&nbsp;', ' ', $request->message)));
    $request->merge(['message_count' => mb_strlen($plainText)]);
    $validated = $request->validate([
        'message' => 'required|string',
        'message_count' => 'numeric|min:5|max:255',
        'parent_id' => 'nullable|exists:chirps,id'
    ], [
            'message.required' => 'Please write something to chirp! 🐤',
            'message_count.min' => 'Your chirp is too short! Make it at least 5 characters. 🐤',
            'message_count.max' => 'Your chirp is too long! Keep it under 255 characters. 🐤',
        ]);

    $chirp = auth()->user()->chirps()->create($validated);
    
    $colors = ['#e57373','#64b5f6','#81c784','#ffb74d','#ba68c8','#4dd0e1'];
    if ($request->filled('tag_name')) {
        $tag = Tag::firstOrCreate(
            ['name' => $request->tag_name],
            ['color' => $colors[array_rand($colors)]] // Random color if created
        );
        $chirp->tags()->syncWithoutDetaching([$tag->id]);
    }

    if ($request->has('tags')) {
        $tagIds = collect($request->tags)->map(function ($name) use ($colors) {
            $tag = Tag::firstOrCreate(
                ['name' => $name],
                ['color' => $colors[array_rand($colors)]] // Added the color here too!
            );
            return $tag->id;
        });

        $chirp->tags()->syncWithoutDetaching($tagIds);
    }

    return back()->with('success', 'Chirp created successfully!');
}




    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp) 
    {
        $oldMessage = old('message', '');
        $oldMessageLength = mb_strlen(trim(strip_tags(str_replace('&nbsp;', ' ', $oldMessage))));

        $chirp->load(['replies.user', 'replies.replies']); 

        return view('chirps.show', compact('chirp'), [
            'availableTags' => Tag::orderBy('name')->get(),
            'oldMessageLength' => $oldMessageLength, 
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        if (!str_contains(url()->previous(), '/edit')) {
            session(['chirp_origin' => url()->previous()]);
        }
        $this->authorize('update', $chirp);
        $oldMessageLength = mb_strlen(trim(strip_tags(str_replace('&nbsp;', ' ', $chirp->message))));

        return view('chirps.edit', compact('chirp'), [
            'availableTags' => Tag::orderBy('name')->get(),
            'oldMessageLength' => $oldMessageLength, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        $plainText = trim(strip_tags(str_replace('&nbsp;', ' ', $request->message)));
        $request->merge(['message_count' => mb_strlen($plainText)]);
        $validated = $request->validate([
            'message' => 'required|string',
            'message_count' => 'numeric|min:5|max:255',
            'parent_id' => 'nullable|exists:chirps,id'
        ], [
            'message.required' => 'Please write something to chirp! 🐤',
            'message_count.min' => 'Your chirp is too short! Make it at least 5 characters. 🐤',
            'message_count.max' => 'Your chirp is too long! Keep it under 255 characters. 🐤',
        ]);

        $chirp->update($validated);

        // 5. Sync Tags
        $colors = ['#e57373', '#64b5f6', '#81c784', '#ffb74d', '#ba68c8', '#4dd0e1'];
        
        if ($request->filled('tag_name')) {
            $tag = Tag::firstOrCreate(
                ['name' => $request->tag_name],
                ['color' => $colors[array_rand($colors)]]
            );
            $chirp->tags()->syncWithoutDetaching([$tag->id]);
        }

        if ($request->has('tags')) {
            $tagIds = collect($request->tags)->map(function ($name) use ($colors) {
                $tag = Tag::firstOrCreate(
                    ['name' => $name],
                    ['color' => $colors[array_rand($colors)]]
                );
                return $tag->id;
            });
            $chirp->tags()->syncWithoutDetaching($tagIds);
        }

        $url = session()->pull('chirp_origin', url()->previous());
        return redirect($url)->with('success', 'Chirp updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        $chirp->delete();

        return back()->with('success', 'Chirp deleted successfully!');
    }
}
