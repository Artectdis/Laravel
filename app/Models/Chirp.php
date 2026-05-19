<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Tonysm\RichTextLaravel\Attributes\RichTextAttributes;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Chirp extends Model
{
    use HasRichText;
    protected $richTextAttributes = ['content'];
    protected $fillable = [
        'message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function userLike()
    {
        return $this->morphMany(Like::class, 'likeable')
            ->where('user_id', auth()->id());
    }
}