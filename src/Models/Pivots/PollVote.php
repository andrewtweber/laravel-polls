<?php

namespace Andrewtweber\Models\Pivots;

use Andrewtweber\Models\Poll;
use Andrewtweber\Models\PollOption;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User;

/**
 * Class PollVote
 *
 * @package Andrewtweber\Models\Pivots
 *
 * @property int        $poll_id
 * @property int        $user_id
 * @property int        $poll_option_id
 *
 * @property Poll       poll
 * @property User       user
 * @property PollOption pollOption
 */
class PollVote extends Pivot
{
    protected $table = 'poll_votes';

    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(config('polls.models.poll', Poll::class));
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('polls.models.user', User::class));
    }

    /**
     * @return BelongsTo
     */
    public function pollOption(): BelongsTo
    {
        return $this->belongsTo(config('polls.models.option', PollOption::class));
    }
}
