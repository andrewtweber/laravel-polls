<?php
/** @var \Andrewtweber\Models\Poll $poll */
?>

<div class="card mb-3">
    <div class="card-body">
        <p class="mb-4">
            💡 <a href="{{ $poll->url }}" class="text-body"><span class="ml-2">{{ $poll->title }}</span></a>
        </p>
        @if ($showing === 'results')
            @include('polls::results')
        @else
            @include('polls::vote')
        @endif
    </div>
</div>
