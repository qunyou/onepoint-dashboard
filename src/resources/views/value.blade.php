@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'value'))
    {!! $input_array['prepend_str'] !!}
    @if ($input_array['value_type'] == 'image')
        {!! $input_array['input_value'] !!}
    @else
        @if ($input_array['input_value'] === '啟用' || $input_array['input_value'] === '停用')
            {{ __('backend.' . $input_array['input_value']) }}
        @else
            @switch($input_array['value_type'])
                @case('boolean')
                    {{ $input_array['input_value'] ? __('backend.是') : __('backend.否') }}
                    @break
                @case('integer')
                    {{ number_format($input_array['input_value'], $input_array['parameter']['decimals'] ?? 0) }}
                    @break
                @default
                    {{ $input_array['input_value'] }}
            @endswitch
        @endif
    @endif
    {!! $input_array['depend_str'] !!}
@endcomponent
