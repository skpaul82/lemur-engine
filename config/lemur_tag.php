<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TALK SERVICE CONFIG
    |--------------------------------------------------------------------------
    |
    */

    'unknown' => [
        'bot_property' => 'unknown',
        'input' => 'unknown',
        'property' => 'unknown',
        'response' => 'unknown',
        'star' => 'unknown',
        'thatstar' => 'unknown',
        'topicstar' => 'unknown',
        'global_property' => '',
        'var_property' => '',
        ],
    'defaultPosition' => [
        'that' => [0=>1, 1=>1]
    ],
    'normalized' => [
        'that'
    ],
    'stateMax' => [
        'that' => 10,
        'topicstar' => 10,
        'input' => 10,
        'openSrai' => 10,
    ],
    'html' => [
        'br'=>'single',
        'a'=>'wrapped',
        'img'=>'wrapped',
        "strong"=>'wrapped',
    ],
    'recursive' => [
        'srai',
        'sr',
        'random',
        'condition'
    ],
    'global' => [
        'input',
        'that',
        'topic',
        'star',
        'thatstar',
        'topicstar',
        'global',
        'name',
        'request',
        'map',
    ],
    'recursion' => [
        'max' => 10,
        'message'=>'Error - thinking too deeply.'
    ],
    'sentenceSplitters' => ['.','!','?','。','？',';'],
    'commonNormalizations' => [
        '.com'=>' dot com',
        '.co.uk'=>' dot co dot uk',
        '.org'=>' dot org',
        '.io'=>' dot io',
        '.org'=>' dot org',
        '+' => ' PLUS ',
        '/' => ' DIVIDE ',
        '-' => ' SUBTRACT ',
        '*' => ' MULTIPLY ',
    ],

];
