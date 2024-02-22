<?php

declare(strict_types=1);

namespace FriendsOfBotble\EmailLog\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\Forms\GeneralSettingForm;
use Illuminate\Support\ServiceProvider;

class EmailLogServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public const MODULE_NAME = 'email-log';

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/fob-email-log')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->loadMigrations();

        $this->app->register(EventServiceProvider::class);

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::registerItem([
                'id' => 'email-log',
                'priority' => 5,
                'parent_id' => null,
                'name' => __('Email Logs'),
                'icon' => 'ti ti-mail-opened',
                'url' => route('email-logs.index'),
                'permissions' => ['email-logs.index'],
            ]);
        });

        GeneralSettingForm::extend(function (GeneralSettingForm $form) {
            $form->add(
                'keep_email_log_for_days',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/fob-email-log::email-log.settings.keep_log_for_days'))
                    ->value((string) setting('keep_email_log_for_days', 30))
                    ->helperText(trans('plugins/fob-email-log::email-log.settings.keep_log_for_days_description'))
                    ->toArray()
            );
        });
    }
}
