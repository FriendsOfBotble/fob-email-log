<?php

declare(strict_types=1);

namespace Datlechin\EmailLog\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Botble\Base\Facades\DashboardMenu;

class EmailLogServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public const MODULE_NAME = 'email-log';

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/email-log')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->loadMigrations();

        $this->app->register(EventServiceProvider::class);

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'email-log',
                'priority' => 5,
                'parent_id' => null,
                'name' => __('Email Logs'),
                'icon' => 'fa fa-envelope',
                'url' => route('email-logs.index'),
                'permissions' => ['email-logs.index'],
            ]);
        });

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, function (string|null $data = null): string {
            return $data . view('plugins/email-log::settings')->render();
        }, 999);
    }
}
