<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you need to extend the base classes you can do so and add your child
    | class here.
    */
    'models' => [
        'user'        => \Illuminate\Foundation\Auth\User::class,
        'poll'        => \Andrewtweber\Models\Poll::class,
        'option'      => \Andrewtweber\Models\PollOption::class,

        // Pivots
        'vote'       => \Andrewtweber\Models\Pivots\PollVote::class,
        'guest_vote' => \Andrewtweber\Models\Pivots\PollGuestVote::class,
    ],

];
