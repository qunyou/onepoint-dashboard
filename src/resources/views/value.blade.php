@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs-group', $input_array = $formPresenter->setValue($input_setting, 'value'))
    {!! $input_array['prepend_str'] !!}
    <div class="border p-1 bg-light">
        @if ($input_array['input_value'] === '啟用' || $input_array['input_value'] === '停用')
            {{ __('dashboard::backend.' . $input_array['input_value']) }}
        @else
            @switch($input_array['value_type'])
                @case('image')
                @case('file')
                @case('raw_html')
                    {!! $input_array['input_value'] !!}
                    @break
                @case('boolean')
                    {{ $input_array['input_value'] ? __('dashboard::backend.是') : __('dashboard::backend.否') }}
                    @break
                @case('integer')
                    {{ number_format($input_array['input_value'], $input_array['parameter']['decimals'] ?? 0) }}
                    @break
                @default
                    {{ $input_array['input_value'] }}
            @endswitch
        @endif
    </div>
    {!! $input_array['depend_str'] !!}
@endcomponent
