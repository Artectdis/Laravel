<?php

use App\Models\User;
use function Livewire\Volt\{state, mount};
use Illuminate\Support\Str;

state(['user', 'isBlocking']);

mount(function (User $user) {
    $this->user = $user; // is the person im blocks ($user) blocked by me before?
    $this->isBlocking = auth()->user()?->blocks()->where('blocked_user_id', $user->id)->exists() ?? false;
});

$toggleBlock = function () {
    if (auth()->guest()) {
        return $this->redirect('/login');
    }

    auth()->user()->blocks()->toggle($this->user->id);
    $this->isBlocking = !$this->isBlocking;
};

?>

<div><x-ts-dropdown.items wire:click="toggleBlock" :text="($isBlocking ? 'Unblock ' : 'Block ') . '<strong>&nbsp' . Str::limit($user->name, 18) . '</strong>'" /></div>
