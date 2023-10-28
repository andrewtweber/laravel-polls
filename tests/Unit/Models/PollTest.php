<?php

namespace Andrewtweber\Tests\Unit\Models;

use Andrewtweber\Models\Poll;
use Andrewtweber\Models\PollOption;
use Andrewtweber\Tests\LaravelTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PollTest extends LaravelTestCase
{
    /**
     * @test
     */
    public function help_text()
    {
        $poll = Poll::create([
            'title' => 'Test Poll',
        ]);

        $poll->min_choices = 1;
        $poll->max_choices = 1;
        $this->assertNull($poll->help_text);

        $poll->min_choices = 2;
        $poll->max_choices = 2;
        $this->assertSame('Select 2 choices.', $poll->help_text);

        $poll->min_choices = 1;
        $poll->max_choices = 2;
        $this->assertSame('Select up to 2 choices.', $poll->help_text);

        $poll->min_choices = 2;
        $poll->max_choices = 3;
        $this->assertSame('Select 2-3 choices.', $poll->help_text);

        $poll->options()->saveMany([
            new PollOption(['text' => 'Opt 1']),
            new PollOption(['text' => 'Opt 2']),
        ]);
        $poll->min_choices = 1;
        $poll->max_choices = 2;
        $this->assertSame('Select all that apply.', $poll->help_text);
    }
}
