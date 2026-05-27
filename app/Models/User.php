<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail; // This is the Interface
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait; // This is the Trait
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'bio', 'phone_number', 'birthday', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, MustVerifyEmailTrait; 

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function chirps(): HasMany
    {
        return $this->hasMany(Chirp::class);
    }
    
    public function likes(): HasMany
    {
       return $this->hasMany(Like::class); 
    }
    
    public function blocks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocks', 'user_id', 'blocked_user_id')
                    ->withTimestamps();
    }

    public function blockedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'blocks', 'blocked_user_id', 'user_id')
                    ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follower_user', 'follower_id', 'following_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follower_user', 'following_id', 'follower_id');
    }
    
    public function getAvatarUrlAttribute()
    {
    if (!$this->avatar) {
        return 'https://avatars.laravel.cloud/' . urlencode($this->email);
    }

    

    return env('SUPABASE_PUBLIC_URL') . $this->avatar;
    }

}
