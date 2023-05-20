@extends(BaseHelper::getAdminMasterLayoutTemplate())

@php
    $bodies = [
        'html_body' => trans('plugins/email-log::email-log.html_body'),
        'text_body' => trans('plugins/email-log::email-log.text_body'),
        'raw_body' => trans('plugins/email-log::email-log.raw_body'),
        'debug_info' => trans('plugins/email-log::email-log.debug_info'),
    ];
@endphp

@section('content')
    <h4 class="fs-4 mb-3">{{ trans('plugins/email-log::email-log.viewing_email_log', ['name' => $emailLog->subject, 'id' => $emailLog->id]) }}</h4>

    <div class="card">
        <div class="card-header">{{ trans('plugins/email-log::email-log.envelope') }}</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="created_at" class="form-label">{{ trans('core/base::tables.created_at') }}</label>
                        <input type="text" id="created_at" class="form-control" value="{{ $emailLog->created_at }}" disabled>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from" class="form-label">{{ trans('plugins/email-log::email-log.from') }}</label>
                        <input type="text" id="from" class="form-control" value="{{ $emailLog->from }}" disabled>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to" class="form-label">{{ trans('plugins/email-log::email-log.to') }}</label>
                        <input type="text" id="to" class="form-control" value="{{ $emailLog->to }}" disabled>
                    </div>
                </div>

                @if($emailLog->cc)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cc" class="form-label">{{ trans('plugins/email-log::email-log.cc') }}</label>
                            <input type="text" id="cc" class="form-control" value="{{ $emailLog->cc }}" disabled>
                        </div>
                    </div>
                @endif

                @if($emailLog->bcc)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bcc" class="form-label">{{ trans('plugins/email-log::email-log.bcc') }}</label>
                            <input type="text" id="bcc" class="form-control" value="{{ $emailLog->bcc }}" disabled>
                        </div>
                    </div>
                @endif

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="subject" class="form-label">{{ trans('plugins/email-log::email-log.subject') }}</label>
                        <input type="text" id="subject" class="form-control" value="{{ $emailLog->subject }}" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <ul class="nav nav-tabs" id="tab" role="tablist">
            @foreach($bodies as $key => $value)
                <li class="nav-item" role="presentation">
                    <button @class(['nav-link', 'active' => $loop->first]) id="{{ $key }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $key }}-tab-pane" type="button" role="tab" aria-controls="{{ $key }}-tab-pane" aria-selected="{{ $loop->first }}">
                        {{ $value }}
                    </button>
                </li>
            @endforeach
        </ul>
        <div class="tab-content" id="tabContent">
            @foreach($bodies as $key => $value)
                <div @class(['tab-pane fade', 'show active' => $loop->first]) id="{{ $key }}-tab-pane" role="tabpanel" aria-labelledby="{{ $key }}-tab" tabindex="0">
                    @if(in_array($key, ['text_body', 'raw_body', 'debug_info'], true))
                        <textarea class="form-control" id="{{ $key }}" rows="15" disabled>{{ $emailLog->$key }}</textarea>
                    @else
                        <iframe width="100%" id="{{ $key }}" srcdoc="{{ $emailLog->$key }}"></iframe>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('footer')
    <script>
        $(document).ready(function () {
            for (const element of ['text_body', 'raw_body']) {
                Botble.initCodeEditor(element);
            }

            $('#html_body').each(function () {
                $(this).height($(this).contents().find('html').height());
            });
        });
    </script>
@endpush
