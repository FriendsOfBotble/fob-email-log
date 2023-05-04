<?php

declare(strict_types=1);

namespace Datlechin\EmailLog\Models;

use Illuminate\Database\Eloquent\MassPrunable;
use Botble\Base\Models\BaseQueryBuilder;
use Botble\Base\Models\BaseModel;
use Carbon\Carbon;

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
