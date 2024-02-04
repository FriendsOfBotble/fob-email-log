<?php

declare(strict_types=1);

namespace FriendsOfBotble\EmailLog;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('email_logs');
    }
}
