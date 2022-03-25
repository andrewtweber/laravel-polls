<?php

namespace Andrewtweber\Models;

use Andrewtweber\Models\Pivots\PollGuestVote;
use Andrewtweber\Models\Pivots\PollVote;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

/**
 * Class PollOption
 *
 * @package Andrewtweber\Models
 *
 * @property int                        $id
 * @property int                        $poll_id
 * @property string                     $text
 * @property int                        $weight
 * @property Carbon                     $created_at
 * @property Carbon                     $updated_at
 *
 * @property int                        $value
 * @property string                     $color
 * @property int                        $total_votes
 *
 * @property Poll                       poll
 * @property Collection|PollVote[]      votes
 * @property Collection|PollGuestVote[] guestVotes
 * @property Collection|User[]          users
 */
class PollOption extends Model
{
    protected $fillable = [
        'text',
        'weight',
    ];

    /**
     * @return BelongsTo
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    /**
     * @return HasMany
     */
    public function guestVotes(): HasMany
    {
        return $this->hasMany(PollGuestVote::class);
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'poll_votes');
    }

    /**
     * These properties are appended by the SQL query
     * @see Poll::loadOptions()
     *
     * @return int
     */
    public function getValueAttribute(): int
    {
        return $this->votes_count + $this->guest_votes_count;
    }

    /**
     * @param int $max_votes
     *
     * @return int
     */
    public function width(int $max_votes): int
    {
        $max = $max_votes ?: 1;

        return (int)(100 * ($this->value / $max));
    }

    /**
     * @return string
     */
    public function getColorAttribute(): string
    {
        return match ($this->id % 5) {
            0 => 'primary',
            1 => 'info',
            2 => 'warning',
            3 => 'danger',
            default => 'success',
        };
    }

    /**
     * @return int
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->value;
    }
}
