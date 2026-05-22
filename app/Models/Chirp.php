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
        'message', 'parent_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function replies()
    {
        return $this->hasMany(Chirp::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Chirp::class, 'parent_id');
    }
    
    public function userLike()
    {
        return $this->morphMany(Like::class, 'likeable')
            ->where('user_id', auth()->id());
    }
}