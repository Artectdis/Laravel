<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use App\Models\Tag;
use Illuminate\Support\Str;
use App\Notifications\NewChirp;
use App\Notifications\NewChirpReply;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

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
        'availableTags' => Tag::withCount('chirps')
            ->orderBy('chirps_count', 'desc')
            ->get()
            ->map(fn($tag) => [
                'caption' => $tag->chirps_count . ' ' . Str::plural('Chirp', $tag->chirps_count),
                'name' => $tag->name,           
                'color' => $tag->color,         
            ])
            ->toArray(),
            'oldMessageLength' => $oldMessageLength,
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
        $rawHtml = $request->message ?? '';
        $lineCount = substr_count($rawHtml, '<br>') + substr_count($rawHtml, '<br/>') + substr_count($rawHtml, "\n") + 1;
        $plainText = trim(strip_tags(str_replace('&nbsp;', ' ', $rawHtml)));

        $request->merge([
            'message_count' => mb_strlen($plainText),
            'message_lines' => $lineCount,
        ]);
        $validated = $request->validate([
            'message' => 'required|string',
            'message_count' => 'numeric|min:5|max:255',
            'message_lines' => 'numeric|max:20',
            'parent_id' => 'nullable|exists:chirps,id',
        ], [
            'message.required' => 'Please write something to chirp! 🐤',
            'message_count.min' => 'Your chirp is too short! Make it at least 5 characters. 🐤',
            'message_count.max' => 'Your chirp is too long! Keep it under 255 characters. 🐤',
            'message_lines.max' => 'Your chirp is too tall! Keep it under 20 lines. 🐤',
        ]);

        $chirp = auth()->user()->chirps()->create($validated);
        $chirp->load('user');

        $colors = ['#e57373', '#64b5f6', '#81c784', '#ffb74d', '#ba68c8', '#4dd0e1'];
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

        $users = auth()->user()->followers()->get();
        foreach ($users as $user) {
            $user->notify(new NewChirp($chirp));
        }

        if ($request->filled('parent_id')) {
            $parentChirp = Chirp::find($request->parent_id);
            if ($parentChirp && $parentChirp->user_id !== auth()->id()) {
                $parentChirp->user->notify(new NewChirpReply($chirp));
            }
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

        return view('chirps.show', compact('chirp'), [
            'availableTags' => Tag::withCount('chirps')
                ->orderBy('chirps_count', 'desc')
                ->get(),
            'oldMessageLength' => $oldMessageLength,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        if (! str_contains(url()->previous(), '/edit')) {
            session(['chirp_origin' => url()->previous()]);
        }
        $this->authorize('update', $chirp);
        $oldMessageLength = mb_strlen(trim(strip_tags(str_replace('&nbsp;', ' ', $chirp->message))));

        return view('chirps.edit', [
            'chirp' => $chirp,
            'oldMessageLength' => $oldMessageLength,
            'availableTags' => Tag::withCount('chirps')
                ->orderBy('chirps_count', 'desc')
                ->get()
                ->map(fn($tag) => [
                    'caption' => $tag->chirps_count . ' ' . Str::plural('Chirp', $tag->chirps_count),
                    'name' => $tag->name,
                    'color' => $tag->color,
                ])
                ->toArray(),
            'chirpTags' => $chirp->tags->map(fn($tag) => [
                'name' => $tag->name,
                'color' => $tag->color,
            ])->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        $rawHtml = $request->message ?? '';
        $lineCount = substr_count($rawHtml, '<br>') + substr_count($rawHtml, '<br/>') + substr_count($rawHtml, "\n") + 1;
        $plainText = trim(strip_tags(str_replace('&nbsp;', ' ', $rawHtml)));

        $request->merge([
            'message_count' => mb_strlen($plainText),
            'message_lines' => $lineCount,
        ]);

        $validated = $request->validate([
            'message' => 'required|string',
            'message_count' => 'numeric|min:5|max:255',
            'message_lines' => 'numeric|max:20',
            'parent_id' => 'nullable|exists:chirps,id',
        ], [
            'message.required' => 'Please write something to chirp! 🐤',
            'message_count.min' => 'Your chirp is too short! Make it at least 5 characters. 🐤',
            'message_count.max' => 'Your chirp is too long! Keep it under 255 characters. 🐤',
            'message_lines.max' => 'Your chirp is too tall! Keep it under 20 lines. 🐤',
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
        $parentId = $chirp->parent_id; 
        $chirp->delete();
        $redirectTo = $parentId ? "/chirps/{$parentId}" : '/';
        return redirect($redirectTo)->with('success', 'Chirp deleted successfully!');
    }
}
