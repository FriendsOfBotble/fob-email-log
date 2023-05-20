<?php

declare(strict_types=1);

namespace Datlechin\EmailLog\Tables;

use Botble\Base\Facades\BaseHelper;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Contracts\Routing\UrlGenerator;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Database\Eloquent\Builder;
use Datlechin\EmailLog\Models\EmailLog;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class EmailLogTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator)
    {
        parent::__construct($table, $urlGenerator);

        if (! $this->request()->user()->hasAnyPermission(['email-logs.show', 'email-logs.destroy'])) {
            $this->hasOperations = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function (EmailLog $item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function (EmailLog $item) {
                return BaseHelper::formatDateTime($item->created_at);
            })
            ->editColumn('from', function (EmailLog $item) {
                return str_replace('<', '&lt;', $item->from);
            })
            ->addColumn('operations', function (EmailLog $item) {
                return $this->getOperations(
                    edit: null,
                    delete: 'email-logs.destroy',
                    item: $item,
                    extra: view('plugins/email-log::partials.show-button', compact('item'))->render()
                );
            });

        return $this->toJson($data);
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(
            route('email-logs.deletes'),
            'email-logs.delete',
        );
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = EmailLog::query()
            ->select([
                'id',
                'from',
                'to',
                'subject',
                'created_at',
            ])
            ->latest();

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'from' => [
                'title' => trans('plugins/email-log::email-log.from'),
            ],
            'to' => [
                'title' => trans('plugins/email-log::email-log.to'),
            ],
            'subject' => [
                'title' => trans('plugins/email-log::email-log.subject'),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }
}
