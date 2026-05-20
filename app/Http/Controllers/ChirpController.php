<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
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
        $chirps = Chirp::with('user:id,name,email,email_verified_at')->latest()->take(10)->get();

        $oldMessage = old('message', '');
        $oldMessageLength = mb_strlen(trim(strip_tags(str_replace('&nbsp;', ' ', $oldMessage))));

    return view('home', compact('chirps', 'oldMessageLength'));
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
        $decodedMessage = str_replace('&nbsp;', ' ', $request->message);
        $plainTextMessage = trim(strip_tags($decodedMessage)); 
        $plainTextLength = mb_strlen($plainTextMessage);
        $request->merge(['message_count' => $plainTextLength]);

        if ($plainTextLength < 5) {
        return back()
            ->withInput()
            ->withErrors([
                'message' => 'Your chirp is too short! Make it at least 5 characters. 🐤',
            ]);
        }
        $request->merge(['message_count' => $plainTextLength]);
        $validated = $request->validate([
            'message' => 'required|string',
            'message_count' => 'numeric|max:255',
        ], [
            'message.required' => 'Please write something to chirp! 🐤',
            'message_count.max' => 'Your chirp is too long! Keep it under 255 characters. 🐤',
        ]);

        auth()->user()->chirps()->create($validated);

        return redirect('/')->with('success', 'Chirp created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        $oldMessageLength = mb_strlen(trim(strip_tags(str_replace('&nbsp;', ' ', $chirp->message))));

        return view('chirps.edit', compact('chirp', 'oldMessageLength'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255|min:5',
        ], [
            'message.required' => 'Please write something to chirp! 🐤',
            'message.max' => 'Your chirp is too long! Keep it under 255 characters. 🐤',
            'message.min' => 'Your chirp is too short! Make it at least 5 characters. 🐤'
        ]);
 
        $chirp->update($validated);

        return redirect('/')->with('success', 'Chirp updated successfully!');
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
