<?php
return [
    /*
    |--------------------------------------------------------------------------
    | DEFAULT SETTINGS
    |--------------------------------------------------------------------------
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Show Detailed Error Message
    |--------------------------------------------------------------------------
    |
    | When this is set to true, the admin users will see a more detailed error message
    | e.g. detailed SQL errors
    | This is not recommended as it could display more information than you require to your admin users
    | But it can be useful when it is hard for admin users to access logs
    | All detailed error message are hidden from non-admins
    |
    | true or false
    */

    'show_detailed_error_messages' => env('SHOW_DETAILED_ADMIN_ERRORS', false),

    /*
    |--------------------------------------------------------------------------
    | Max User Input
    |--------------------------------------------------------------------------
    |
    | Set a maximum amount of chars that the user is allowed to send in a single input
    |
    */

    'max_user_char_input' => env('MAX_USER_INPUT_CHARS', 255),

    /*
    |--------------------------------------------------------------------------
    | Default Bot Image
    |--------------------------------------------------------------------------
    |
    | Location of the images used in the widget gui
    |
    */

    'default_bot_image' => 'widgets/robot.png',

    /*
    |--------------------------------------------------------------------------
    | Default Client Image
    |--------------------------------------------------------------------------
    |
    | Location of the images used in the widget gui
    |
    */

    'default_client_image' => 'widgets/user.png',

    /*
    |--------------------------------------------------------------------------
    | Required Bot Properties
    |--------------------------------------------------------------------------
    |
    | A list of required bot properties
    | Used for formatting the bot property ui
    |
    */

    'required_bot_properties' => [
        'age',
        'baseballteam',
        'birthday',
        'birthplace',
        'botmaster',
        'boyfriend',
        'build',
        'celebrities',
        'celebrity',
        'class',
        'email',
        'emotions',
        'ethics',
        'etype',
        'family',
        'favoriteactor',
        'favoriteactress',
        'favoriteartist',
        'favoriteauthor',
        'favoriteband',
        'favoritebook',
        'favoritecolor',
        'favoritefood',
        'favoritemovie',
        'favoritesong',
        'favoritesport',
        'feelings',
        'footballteam',
        'forfun',
        'friend',
        'friends',
        'gender',
        'genus',
        'girlfriend',
        'hockeyteam',
        'kindmusic',
        'kingdom',
        'language',
        'location',
        'looklike',
        'master',
        'msagent',
        'name',
        'nationality',
        'order',
        'orientation',
        'party',
        'phylum',
        'president',
        'question',
        'religion',
        'sign',
        'size',
        'species',
        'talkabout',
        'version',
        'vocabulary',
        'wear',
        'website',
        'facebook',
        'twitter',
        'linkedin',
        'tiktok',
        'instragram',


    ]
];
