@extends(BaseHelper::getAdminMasterLayoutTemplate())

@php
    $bodies = [
        'html_body' => __('HTML'),
        'text_body' => __('Text'),
        'raw_body' => __('Raw'),
        'debug_info' => __('Debug Info'),
    ];
@endphp

@section('content')
    <h4 class="fs-4 mb-3">{{ __('Email Logs') }}</h4>

    <div class="card">
        <div class="card-header">{{ __('Envelope') }}</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="created_at" class="form-label">{{ __('Created at') }}</label>
                        <input type="text" id="created_at" class="form-control" value="{{ $emailLog->created_at }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from" class="form-label">{{ __('From') }}</label>
                        <input type="text" id="from" class="form-control" value="{{ $emailLog->from }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to" class="form-label">{{ __('To') }}</label>
                        <input type="text" id="to" class="form-control" value="{{ $emailLog->to }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cc" class="form-label">{{ __('Cc') }}</label>
                        <input type="text" id="cc" class="form-control" value="{{ $emailLog->cc }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bcc" class="form-label">{{ __('Bcc') }}</label>
                        <input type="text" id="bcc" class="form-control" value="{{ $emailLog->bcc }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="subject" class="form-label">{{ __('Subject') }}</label>
                        <input type="text" id="subject" class="form-control" value="{{ $emailLog->subject }}" readonly>
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
                        <textarea class="form-control" id="{{ $key }}" rows="15" readonly>{{ $emailLog->$key }}</textarea>
                    @else
                        <iframe width="100%" srcdoc="{{ $emailLog->$key }}"></iframe>
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

            $('iframe').each(function () {
                $(this).height($(this).contents().find('html').height());
            });
        });
    </script>
@endpush
