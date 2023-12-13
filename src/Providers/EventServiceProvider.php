<?php

declare(strict_types=1);

namespace FriendsOfBotble\EmailLog\Providers;

use FriendsOfBotble\EmailLog\Listeners\EmailLogger;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            EmailLogger::class,
        ],
    ];
}
