<?php

declare(strict_types=1);

namespace FriendsOfBotble\EmailLog\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use FriendsOfBotble\EmailLog\Models\EmailLog;
use Illuminate\Database\Eloquent\Builder;

class EmailLogTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(EmailLog::class)
            ->addActions([
                Action::make('view')
                    ->route('email-logs.edit')
                    ->label(trans('core/base::tables.view'))
                    ->icon('ti ti-eye'),
                DeleteAction::make()->route('email-logs.destroy'),
            ])
            ->queryUsing(fn (Builder $query) => $query->select([
                'id',
                'from',
                'to',
                'subject',
                'created_at',
            ])->latest())
            ->addColumns([
                IdColumn::make(),
                Column::make('subject')->label(trans('plugins/fob-email-log::email-log.subject')),
                FormattedColumn::make('from')
                    ->label(trans('plugins/fob-email-log::email-log.from'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $emailLog = $column->getItem();

                        return str_replace('<', '&lt;', $emailLog->from);
                    }),
                Column::make('to')->label(trans('plugins/fob-email-log::email-log.to')),
                CreatedAtColumn::make()->dateFormat(BaseHelper::getDateTimeFormat()),
            ])
            ->addBulkAction(DeleteBulkAction::make());
    }
}
