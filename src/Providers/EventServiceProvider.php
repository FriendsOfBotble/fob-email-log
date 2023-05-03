<?php

declare(strict_types=1);

namespace Datlechin\EmailLog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Datlechin\EmailLog\Listeners\EmailLogger;
use Illuminate\Mail\Events\MessageSent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            EmailLogger::class,
        ],
    ];
}
