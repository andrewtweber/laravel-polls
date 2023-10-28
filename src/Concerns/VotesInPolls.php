<?php

namespace Andrewtweber\Concerns;

use Andrewtweber\Models\Pivots\PollVote;
use Andrewtweber\Models\Poll;
use Andrewtweber\Models\PollOption;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection<PollOption> pollOptions
 * @property Collection<PollVote>   votes
 */
trait VotesInPolls
{
    /**
     * @return BelongsToMany
     */
    public function pollOptions(): BelongsToMany
    {
        return $this->belongsToMany(config('polls.models.option', PollOption::class), 'poll_votes');
    }

    /**
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(config('polls.models.vote', PollVote::class));
    }

    /**
     * @param Poll $poll
     *
     * @return bool
     */
    public function votedIn(Poll $poll): bool
    {
        return $this->votes()->where('poll_id', $poll->getKey())->count() > 0;
    }
}
