<?php

declare(strict_types=1);

namespace Datlechin\EmailLog\Http\Controllers;

use Datlechin\EmailLog\Providers\EmailLogServiceProvider;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Symfony\Component\HttpFoundation\Response;
use Datlechin\EmailLog\Tables\EmailLogTable;
use Botble\Base\Events\DeletedContentEvent;
use Datlechin\EmailLog\Models\EmailLog;
use Illuminate\Contracts\View\View;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Facades\Assets;
use Illuminate\Http\Request;
use Exception;

class EmailLogController extends BaseController
{
    public function index(EmailLogTable $emailLogTable): View|Response
    {
        PageTitle::setTitle(trans('plugins/email-log::email-log.name'));

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

        PageTitle::setTitle(trans('plugins/email-log::email-log.viewing_email_log', ['name' => $emailLog->subject, 'id' => $emailLog->id]));

        return view('plugins/email-log::email-logs.show', compact('emailLog'));
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
