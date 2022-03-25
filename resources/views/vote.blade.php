<?php
/** @var \Andrewtweber\Models\Poll $poll */
?>

<form method="POST" action="{{ $poll->url }}">
    @csrf

    @if ($poll->help_text)
        <p class="mb-2">{{ $poll->help_text }}</p>
    @endif

    @foreach ($poll->options as $option)
        <div class="form-check mb-1">
            <label class="form-check-label">
                <input type="{{ $poll->input_type }}" class="form-check-input"
                       name="options[]" value="{{ $option->id }}"
                        {{ $poll->input_type === 'radio' ? 'required' : '' }}
                        {{ ($user && $user->pollOptions->contains($option)) ? 'checked' : '' }}
                        {{ $guest_votes->firstWhere('poll_option_id', $option->id) ? 'checked' : '' }}
                >
                <span class="d-inline-block ml-2">{{ $option->text }}</span>
            </label>
        </div>
    @endforeach

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i>{{ __('polls.Vote') }}
        </button>
        <a class="btn btn-link"
           href="{{ ($permalink ?? false) ? ($permalink === true ? $poll->url : $permalink) : '/' }}?results">{{ __('polls.See results') }}</a>
    </div>
</form>
