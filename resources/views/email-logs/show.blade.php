@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('plugins/fob-email-log::email-log.envelope') }}
            </x-core::card.title>
        </x-core::card.header>

        <x-core::card.body>
            {!! $form !!}
        </x-core::card.body>
    </x-core::card>

    <x-core::card class="mt-3">
        <x-core::card.header>
            <x-core::tab class="card-header-tabs">
                @foreach($tabs as $key => $value)
                    <x-core::tab.item :id="$key" :label="$value" :is-active="$loop->first" />
                @endforeach
            </x-core::tab>
        </x-core::card.header>

        <x-core::tab.content>
            @foreach($tabs as $key => $value)
                <x-core::tab.pane :id="$key" :is-active="$loop->first">
                    @if(in_array($key, ['text_body', 'raw_body', 'debug_info'], true))
                        <x-core::form.code-editor
                            :name="$key"
                            :value="$emailLog->$key"
                            :disabled="true"
                            mode="html"
                        />
                    @else
                        <iframe width="100%" id="{{ $key }}" srcdoc="{{ $emailLog->$key }}"></iframe>
                    @endif
                </x-core::tab.pane>
            @endforeach
        </x-core::tab.content>
    </x-core::card>
@endsection

@push('footer')
    <script>
        $(document).ready(function () {
            $('iframe').each(function () {
                $(this).height($(this).contents().find('html').height());
            });
        });
    </script>
@endpush
