@inject('formPresenter','Onepoint\Dashboard\Presenters\FormPresenter')
@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'value'))
    {!! $input_array['prepend_str'] !!}
    @if ($input_array['value_type'] == 'image')
        {!! $input_array['input_value'] !!}
    @else
        @if ($input_array['input_value'] === '啟用' || $input_array['input_value'] === '停用')
            {{ __('backend.' . $input_array['input_value']) }}
        @else
            @if ($input_array['value_type'] == 'boolean')
                {{ $input_array['input_value'] ? __('backend.是') : __('backend.否') }}
            @else
                {{ $input_array['input_value'] }}
            @endif
        @endif
    @endif
    {!! $input_array['depend_str'] !!}
@endcomponent
