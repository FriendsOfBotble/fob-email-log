<x-core-setting::section
    :title="trans('datlechin/email-log::email-log.settings.title')"
    :description="trans('datlechin/email-log::email-log.settings.description')"
>
    <x-core-setting::text-input
        name="keep_email_log_for_days"
        type="number"
        :label="trans('datlechin/email-log::email-log.settings.keep_log_for_days')"
        :value="setting('keep_email_log_for_days', 30)"
        :helper-text="trans('datlechin/email-log::email-log.settings.keep_log_for_days_description')"
    />
</x-core-setting::section>
