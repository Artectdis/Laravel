<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
    protected $fillable = ['name', 'color'];
    public function chirps() { return $this->belongsToMany(Chirp::class); }
}