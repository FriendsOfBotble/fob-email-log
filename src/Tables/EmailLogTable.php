<?php

declare(strict_types=1);

namespace Datlechin\EmailLog\Tables;

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

        if (! $this->request()->user()->hasPermission('email-logs.delete')) {
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
                return $item->created_at;
            })
            ->editColumn('from', function (EmailLog $item) {
                return str_replace('<', '&lt;', $item->from);
            })
            ->addColumn('operations', function (EmailLog $item) {
                return $this->getOperations(
                    edit: null,
                    delete: 'email-logs.destroy',
                    item: $item,
                    extra: view('datlechin/email-log::partials.show-button', compact('item'))->render()
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
                'title' => __('ID'),
                'width' => '20px',
            ],
            'from' => [
                'title' => __('From'),
            ],
            'to' => [
                'title' => __('To'),
            ],
            'subject' => [
                'title' => __('Subject'),
            ],
            'created_at' => [
                'title' => __('Created at'),
                'width' => '100px',
            ],
        ];
    }
}
