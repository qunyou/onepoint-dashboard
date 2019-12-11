@inject('formPresenter','Onepoint\Dashboard\Presenters\FormPresenter')
@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'value'))
    {!! $input_array['prepend_str'] !!}
    @if ($input_array['value_type'] == 'image')
        {!! $input_array['input_value'] !!}
    @else
        {{ $input_array['input_value'] }}
    @endif
    {!! $input_array['depend_str'] !!}
@endcomponent

{{-- if (!isset($input_array['input_value'])) {
    $input_array['input_value'] = '';
} else {

    // 選單類型資料
    if (isset($option)) {

        // checkbox 的值不好處理，直接用 checkbox 類型的表單來顯示
        if (isset($option[$input_value])) {
            $input_value = $option[$input_value];
        }
    }
}
--}}
