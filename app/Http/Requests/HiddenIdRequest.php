<?php

namespace App\Http\Requests;

use App\Models\Bot;
use App\Models\CategoryGroup;
use App\Models\Client;
use App\Models\Conversation;
use App\Models\Language;
use App\Models\Map;
use App\Models\Set;
use App\Models\Turn;
use App\Models\WordSpellingGroup;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\BotKey;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class HiddenIdRequest extends FormRequest
{


    /**
     * Handle a failed validation attempt.
     * We need to untransform some data
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {

        $postItems = request()->post();

        foreach ($postItems as $key => $value) {
            if (substr($key, -3)=='_id') {
                $item = false;

                //if this is an array of value...
                //just return it
                if (is_array($value)) {
                    return $value;
                }


                if ($key == 'bot_id') {
                    $item = Bot::findOrFail($value);
                } elseif ($key == 'language_id') {
                    $item = Language::findOrFail($value);
                } elseif ($key == 'category_group_id' && !is_array($value)) {
                    $item = CategoryGroup::findOrFail($value);
                } elseif ($key == 'set_id') {
                    $item = Set::findOrFail($value);
                } elseif ($key == 'map_id') {
                    $item = Map::findOrFail($value);
                } elseif ($key == 'word_spelling_group_id') {
                    $item = WordSpellingGroup::findOrFail($value);
                } elseif ($key == 'client_id') {
                    $item = Client::findOrFail($value);
                } elseif ($key == 'conversation_id') {
                    $item = Conversation::findOrFail($value);
                } elseif ($key == 'conversation_id') {
                    $item = Conversation::findOrFail($value);
                } elseif ($key == 'turn_id') {
                    $item = Turn::findOrFail($value);
                } else {
                    if (!is_array($value)) {
                        throw new \Exception('Unknown id item: ' . $key);
                    }
                }

                if ($item) {
                    //validation has failed ... we need to un-transform the id back to a slug
                    request()->merge([
                        $key => $item->slug,
                    ]);
                }
            }
        }

        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
