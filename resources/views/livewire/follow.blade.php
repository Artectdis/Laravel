<?php

use App\Models\User;
use function Livewire\Volt\{state, mount};
use Illuminate\Support\Str;

state(['user', 'isFollowing']);

mount(function (User $user) {
    $this->user = $user; // is the person im following ($user) followed by me before?
    $this->isFollowing = auth()->user()->following()->where('following_id', $user->id)->exists();
});

$toggleFollow = function () {
    auth()->user()->following()->toggle($this->user->id);
    $this->isFollowing = !$this->isFollowing;
};

?>

<div><x-ts-dropdown.items wire:click="toggleFollow" :text="($isFollowing ? 'Unfollow ' : 'Follow ') . '<strong>&nbsp' . Str::limit($user->name, 18) . '</strong>'" /></div>
