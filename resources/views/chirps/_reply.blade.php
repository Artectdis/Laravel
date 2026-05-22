<div class="ml-6 mt-4">
    <x-chirp :chirp="$reply" />
    @foreach ($reply->replies as $nestedReply)
        @include('chirps._reply', ['reply' => $nestedReply])
    @endforeach
</div>
