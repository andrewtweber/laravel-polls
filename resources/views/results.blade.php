<?php
/** @var \Andrewtweber\Models\Poll $poll */
?>

@foreach ($poll->options as $option)
    <div class="row mb-4">
        <div class="col-4" style="line-height:1.25">
            {{ $option->text }}
        </div>
        <div class="col-8">
            <div class="progress" style="height:20px">
                <div class="progress-bar progress-bar-striped bg-{{ $option->color }} text-left"
                     role="progressbar"
                     aria-valuenow="{{ $option->value }}" style="width:{{ $option->width($poll->max_votes) }}%"
                     aria-valuemin="0" aria-valuemax="{{ $poll->max_votes }}">
                    @if ($user && $user->pollOptions->contains($option))
                        <i class="fas fa-check ml-2"></i>
                    @elseif ($guest_votes->firstWhere('poll_option_id', $option->id))
                        <i class="fas fa-check ml-2"></i>
                    @endif
                </div>
            </div>
            @if ($show_counts ?? false)
                <span class="text-muted">{{ trans_choice('polls.num_votes', $option->total_votes) }}</span>
            @endif
        </div>
    </div>
@endforeach

<p class="mb-0">
    <a href="{{ ($permalink ?? false) ? ($permalink === true ? $poll->url : $permalink) : '/' }}?vote">
        <i class="fas fa-poll mr-2"></i>{{ (($user && $user->votedIn($poll)) || count($guest_votes) > 0) ? __('polls.Change my vote') : __('polls.Vote') }}
    </a>
</p>
