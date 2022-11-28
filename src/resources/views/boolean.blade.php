@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs-group', $input_array = $formPresenter->setValue($input_setting, 'text'))
    <div class="form-check form-check-inline pt-2 ms-2">
        <input type="checkbox" class="form-check-input" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" value="1" {{ $input_array['input_value'] ? ' checked' : '' }} {!! $input_array['attribute'] !!}>
        <label class="form-check-label" for="{{ $input_array['input_name'] }}">{{ isset($input_array['option']) ? $input_array['option'][0] : __('dashboard::backend.啟用') }}</label>
    </div>
@endcomponent