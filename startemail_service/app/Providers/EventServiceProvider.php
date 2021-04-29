<?php

namespace App\Providers;

use App\Events\EmailStoredEvent;
use App\Events\FallbackEvent;
use App\Listeners\ProducerEmailStoredListener;
use App\Listeners\ProducerSecondaryServiceFallbackListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EmailStoredEvent::class => [
            ProducerEmailStoredListener::class
        ],
        FallbackEvent::class => [
            ProducerSecondaryServiceFallbackListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
