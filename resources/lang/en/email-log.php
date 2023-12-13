<?php

declare(strict_types=1);

return [
    'name' => 'Email Log',
    'settings' => [
        'keep_log_for_days' => 'Keep email logs for days',
        'keep_log_for_days_description' => 'You need to set up the cron job to delete the email log. See the documentation for more information.',
    ],
    'viewing_email_log' => 'Viewing :name #:id',
    'envelope' => 'Envelope',
    'from' => 'From',
    'to' => 'To',
    'cc' => 'CC',
    'bcc' => 'BCC',
    'subject' => 'Subject',
    'html_body' => 'HTML',
    'text_body' => 'Text',
    'raw_body' => 'Raw',
    'debug_info' => 'Debug Info',
];
