<?php

declare(strict_types=1);

namespace FriendsOfBotble\EmailLog\Models;

use Botble\Base\Models\BaseModel;
use Botble\Base\Models\BaseQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;

class EmailLog extends BaseModel
{
    use MassPrunable;

    protected $table = 'email_logs';

    protected $fillable = [
        'from',
        'to',
        'cc',
        'bcc',
        'subject',
        'html_body',
        'text_body',
        'raw_body',
        'debug_info',
    ];

    public function prunable(): BaseQueryBuilder
    {
        return static::where(
            'created_at',
            '<=',
            Carbon::now()->subDays((int) setting('keep_email_log_for_days', 30))
        );
    }
}
