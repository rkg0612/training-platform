<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    | Twilio configuration
    |
    */
    'sid' => env('TWILIO_ACCOUNT_SID', null),
    'token' => env('TWILIO_AUTH_TOKEN', null),

    'twilio_from' => env('TWILIO_FROM', '+18434080491'),
    'video_day_body' => 'New video of the day has been posted, login to your training site to watch the video. - Webinarinc Admin',
    'assign_mod_body' => 'A new module or playlist (mod_name) has been assigned to you. Please complete it before (due_date). Please click this link to go to the module / playlist: (mod_link)',
];
