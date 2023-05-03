<x-core-setting::section
    :title="__('Email Log')"
    :description="__('Configure the email log')"
>
    <x-core-setting::text-input
        name="keep_email_log_for_days"
        type="number"
        :label="__('Keep email log for days')"
        :value="setting('keep_email_log_for_days', 30)"
        :helper-text="__('You need to set up the cron job to delete the email log. See the documentation for more information.')"
    />
</x-core-setting::section>
