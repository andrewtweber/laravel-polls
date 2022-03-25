<?php

namespace Andrewtweber\Http\Requests;

use Andrewtweber\Models\Poll;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class VoteRequest
 *
 * @package Andrewtweber\Http\Requests
 */
class VoteRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        /** @var Poll $poll */
        $poll = $this->poll;

        return [
            'options' => [
                'required',
                'array',
                'min:' . $poll->min_choices,
                'max:' . $poll->max_choices,
            ],
            'options.*' => [
                Rule::exists('poll_options', 'id')->where(function ($query) {
                    $query->where('poll_id', $this->poll->id);
                }),
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        /** @var Poll $poll */
        $poll = $this->poll;

        $min = $poll->min_choices;
        $max = $poll->max_choices;

        return [
            'options.*.exists' => __('polls.validation.invalid'),
            'options.required' => trans_choice('polls.validation.min_choices', $min),
            'options.min' => trans_choice('polls.validation.min_choices', $min),
            'options.max' => trans_choice('polls.validation.max_choices', $max),
        ];
    }
}
