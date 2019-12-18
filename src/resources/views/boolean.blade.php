@inject('formPresenter','Onepoint\Dashboard\Presenters\FormPresenter')
@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'text'))
    <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="{{ $input_array['input_name'] }}"  name="{{ $input_array['input_name'] }}" value="1" {{ $input_array['input_value'] ? ' checked' : '' }} {!! $input_array['attribute'] !!}>
        <label class="form-check-label" for="{{ $input_array['input_name'] }}">@lang('backend.啟用')</label>
    </div>
@endcomponent