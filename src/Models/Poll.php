<?php

namespace Andrewtweber\Models;

use Andrewtweber\Http\Requests\VoteRequest;
use Andrewtweber\Models\Pivots\PollGuestVote;
use Andrewtweber\Models\Pivots\PollVote;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Slimak\SluggedModel;

/**
 * Class Poll
 *
 * @package Andrewtweber\Models
 *
 * @property int                       $id
 * @property string                    $title
 * @property string                    $slug
 * @property int                       $min_choices
 * @property int                       $max_choices
 * @property bool                      $randomize
 * @property Carbon                    $created_at
 * @property Carbon                    $updated_at
 * @property Carbon                    $deleted_at
 *
 * @property string                    $input_type
 * @property string|null               $help_text
 * @property string                    $url
 * @property int                       $max_votes
 * @property int                       $total_votes
 *
 * @property Collection<PollOption>    options
 * @property Collection<PollVote>      votes
 * @property Collection<PollGuestVote> guestVotes
 */
class Poll extends SluggedModel
{
    use SoftDeletes;

    protected $table = 'polls';

    protected $fillable = [
        'title',
        'min_choices',
        'max_choices',
        'randomize',
    ];

    protected $casts = [
        'randomize' => 'boolean',
    ];

    /**
     * @return string|null
     */
    protected function slugBase(): ?string
    {
        return $this->title;
    }

    /**
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(config('polls.models.option', PollOption::class));
    }

    /**
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(config('polls.models.vote', PollVote::class));
    }

    /**
     * @return HasMany
     */
    public function guestVotes(): HasMany
    {
        return $this->hasMany(config('polls.models.guest_vote', PollGuestVote::class));
    }

    /**
     * @return string
     */
    public function getInputTypeAttribute(): string
    {
        return ($this->min_choices === 1 && $this->max_choices === 1)
            ? 'radio'
            : 'checkbox';
    }

    /**
     * @return string|null
     */
    public function getHelpTextAttribute(): ?string
    {
        $min = $this->min_choices;
        $max = $this->max_choices;

        if ($min === 1 && $max === 1) {
            return null;
        }
        if ($min === $max) {
            return __('polls::polls.min_choices', ['num' => $min]);
        }
        if ($min === 1) {
            if ($max === $this->options()->count()) {
                return __('polls::polls.any_choice');
            }

            return __('polls::polls.max_choices', ['num' => $max]);
        }

        return __('polls::polls.range_choices', ['min' => $min, 'max' => $max]);
    }

    /**
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return '/polls/' . $this->slug;
    }

    /**
     * @return int
     */
    public function getMaxVotesAttribute(): int
    {
        return max($this->options->pluck('value')->all());
    }

    /**
     * @return int
     */
    public function getTotalVotesAttribute(): int
    {
        return array_sum($this->options->pluck('value')->all());
    }

    /**
     * @param string $text
     */
    public function setOptionsTextAttribute(string $text)
    {
        if ($this->exists) {
            throw new InvalidArgumentException("Options can only be set as text on creation");
        }

        $this->save(); // Need the ID to be set

        $values = explode(PHP_EOL, $text);

        $optionClass = config('polls.models.option');

        $options = [];
        foreach ($values as $value) {
            $options[] = new $optionClass([
                'text' => trim($value),
            ]);
        }

        $this->options()->saveMany($options);
    }

    /**
     * Eager-load options in correct order.
     */
    public function loadOptions(): void
    {
        $this->load([
            'options' => function ($query) {
                if ($this->randomize) {
                    $query->inRandomOrder();
                } else {
                    $query->orderBy('weight', 'asc')
                        ->orderBy('id', 'asc');
                }

                $query->withCount('votes')
                    ->withCount('guestVotes');
            },
        ]);
    }

    /**
     * @param User        $user
     * @param VoteRequest $request
     */
    public function storeVotes(User $user, VoteRequest $request): void
    {
        $user->votes()->where('poll_id', $this->getKey())->delete();

        $options = collect($request->get('options'))->mapWithKeys(function ($option_id) {
            return [
                $option_id => [
                    'poll_id' => $this->getKey(),
                ],
            ];
        })->all();

        $user->pollOptions()->syncWithoutDetaching($options);
    }

    /**
     * @param VoteRequest $request
     */
    public function storeGuestVotes(VoteRequest $request): void
    {
        $ip = $request->getClientIp();

        $this->guestVotes()->fromIp($ip)->delete();

        $formatted = collect($request->get('options'))->map(function ($option_id) use ($ip) {
            return [
                'poll_id' => $this->getKey(),
                'poll_option_id' => $option_id,
                'ip_address' => DB::raw("INET6_ATON('{$ip}')"),
            ];
        })->all();

        $this->guestVotes()->insert($formatted);
    }
}
