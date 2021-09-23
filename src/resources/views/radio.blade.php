@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs', $input_array = $formPresenter->setValue($input_setting, 'radio'))
    @foreach ($input_array['option'] as $key => $element)
        <label class="radio-inline">
            <input type="radio" name="{{ $input_array['input_name'] }}" value="{{ $key }}" {{ $input_array['input_value'] == $key ? ' checked' : '' }} {!! $input_array['attribute'] !!}> {{ $element }}
        </label>
    @endforeach
@endcomponent