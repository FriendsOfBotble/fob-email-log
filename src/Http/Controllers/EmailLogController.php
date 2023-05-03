<?php

declare(strict_types=1);

namespace Datlechin\EmailLog\Http\Controllers;

use Datlechin\EmailLog\Providers\EmailLogServiceProvider;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Traits\HasDeleteManyItemsTrait;
use Symfony\Component\HttpFoundation\Response;
use Datlechin\EmailLog\Tables\EmailLogTable;
use Botble\Base\Events\DeletedContentEvent;
use Datlechin\EmailLog\Models\EmailLog;
use Illuminate\Contracts\View\View;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Facades\Assets;
use Illuminate\Http\Request;
use Exception;
use Closure;

class EmailLogController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            if (! request()->user()->hasPermission('email-logs.index')) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index(EmailLogTable $emailLogTable): View|Response
    {
        PageTitle::setTitle(__('Email Log'));

        return $emailLogTable->renderTable();
    }

    public function show(EmailLog $emailLog): View
    {
        Assets::addStylesDirectly([
            'vendor/core/core/base/libraries/codemirror/lib/codemirror.css',
        ])->addScriptsDirectly([
            'vendor/core/core/base/libraries/codemirror/lib/codemirror.js',
            'vendor/core/core/base/libraries/codemirror/lib/css.js',
        ]);

        PageTitle::setTitle(__('Email Log'));

        return view('datlechin/email-log::email-logs.show', compact('emailLog'));
    }

    public function destroy(EmailLog $emailLog, Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        try {
            $emailLog->delete();

            event(new DeletedContentEvent(EmailLogServiceProvider::MODULE_NAME, $request, $emailLog));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $ids = $request->collect('ids');

        if ($ids->isEmpty()) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        $ids->each(function ($id) use ($request, $response) {
            $emailLog = EmailLog::find($id);

            if (! $emailLog) {
                return;
            }

            $emailLog->delete();

            event(new DeletedContentEvent(EmailLogServiceProvider::MODULE_NAME, $request, $emailLog));
        });

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
