<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Queues
    |--------------------------------------------------------------------------
    |
    | Here you may set the default queues to check when firing the console
    | command. You may override this list by supplying a list of queues
    | separated with spaces when firing watchdog:patrol from the cli.
    |
    */

    'queues' => ['default'],

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | These are the notification channels that should be used when a queue
    | is not functioning properly. It is important to note the channels
    | used must have their corresponding config settings setup below.
    |
    | Supported: "mail", "slack"
    |
    */

    'channels' => ['slack'],

    /*
    |--------------------------------------------------------------------------
    | Notification Channel Configuration
    |--------------------------------------------------------------------------
    |
    | These are the configuration options for each of the channels enabled
    | above. Each of the channel's options must be set properly for the
    | notification channel to work and send you the correct messages.
    |
    */

    'channel-config' => [
        'mail'  => [
            'address' => null,
        ],
        'slack' => [
            'webhook' => null,
        ],
    ],
];
