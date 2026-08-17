<?php

use Spatie\Health\Models\HealthCheckResultHistoryItem;
use Spatie\Health\ResultStores\EloquentHealthResultStore;
use Spatie\Health\Notifications\CheckFailedNotification;
use Spatie\Health\Notifications\Notifiable;

return [

    /*
     |--------------------------------------------------------------------------
     | Result Stores
     |--------------------------------------------------------------------------
     */

    'result_stores' => [

        EloquentHealthResultStore::class => [
            'connection' => env('HEALTH_DB_CONNECTION', env('DB_CONNECTION')),
            'model' => HealthCheckResultHistoryItem::class,
            'keep_history_for_days' => 5,
        ],

    ],

    /*
     |--------------------------------------------------------------------------
     | Notifications
     |--------------------------------------------------------------------------
     */

    'notifications' => [

        'enabled' => true,

        'notifications' => [
            CheckFailedNotification::class => ['mail'],
        ],

        'notifiable' => Notifiable::class,

        'throttle_notifications_for_minutes' => 60,

        'throttle_notifications_key' => 'health:latestNotificationSentAt:',

        'only_on_failure' => false,

        'mail' => [

            // ⚠️ Change this to your real email
            'to' => 'gargmukesh05@gmail.com',

            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'City Hang Around'),
            ],
        ],

        'slack' => [

            'webhook_url' => env('HEALTH_SLACK_WEBHOOK_URL', ''),

            'channel' => null,

            'username' => null,

            'icon' => null,
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Oh Dear Integration (Optional)
     |--------------------------------------------------------------------------
     */

    'oh_dear_endpoint' => [

        'enabled' => false,

        'always_send_fresh_results' => true,

        'secret' => env('OH_DEAR_HEALTH_CHECK_SECRET'),

        'url' => '/oh-dear-health-check-results',
    ],

    /*
     |--------------------------------------------------------------------------
     | Horizon Heartbeat
     |--------------------------------------------------------------------------
     */

    'horizon' => [
        'heartbeat_url' => env('HORIZON_HEARTBEAT_URL'),
    ],

    /*
     |--------------------------------------------------------------------------
     | Schedule Heartbeat
     |--------------------------------------------------------------------------
     */

    'schedule' => [
        'heartbeat_url' => env('SCHEDULE_HEARTBEAT_URL'),
    ],

    /*
     |--------------------------------------------------------------------------
     | UI Theme
     |--------------------------------------------------------------------------
     */

    'theme' => 'light',

    /*
     |--------------------------------------------------------------------------
     | Queue Settings
     |--------------------------------------------------------------------------
     */

    'silence_health_queue_job' => true,

    /*
     |--------------------------------------------------------------------------
     | JSON Failure Status
     |--------------------------------------------------------------------------
     */

    'json_results_failure_status' => 200,

    /*
     |--------------------------------------------------------------------------
     | Secret Token (Optional API Access)
     |--------------------------------------------------------------------------
     */

    'secret_token' => env('HEALTH_SECRET_TOKEN'),

    /*
     |--------------------------------------------------------------------------
     | Treat Skipped Checks
     |--------------------------------------------------------------------------
     */

    // 'treat_skipped_as_failure' => false,

    /*
     |--------------------------------------------------------------------------
     | Web Route (Browser Dashboard)
     |--------------------------------------------------------------------------
     */

    'route' => [

        // Enable browser access
        'enabled' => true,

        // URL: /health
        'path' => 'health',

        // Protect with login (VERY IMPORTANT)
        'middleware' => ['auth'],
    ],

];