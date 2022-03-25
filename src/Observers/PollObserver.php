<?php

namespace Andrewtweber\Observers;

use Andrewtweber\Models\Poll;

/**
 * Class PollObserver
 *
 * @package Andrewtweber\Observers
 */
class PollObserver
{
    /**
     * @param Poll $poll
     */
    public function deleting(Poll $poll)
    {
        $poll->guestVotes()->delete();
        $poll->votes()->delete();
        $poll->options()->delete();
    }
}