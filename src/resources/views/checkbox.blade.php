@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs', $input_array = $formPresenter->setValue($input_setting, 'radio'))
<div>
    @foreach ($input_array['option'] as $key => $element)
        <div class="form-check form-check-inline">
            @if (is_array($input_array['input_value']))
                <input type="checkbox" class="form-check-input" id="{{ $input_array['input_name'] . $key }}"  name="{{ $input_array['input_name'] }}[]" value="{{ $key }}" {{ in_array($key, $input_array['input_value']) ? ' checked' : '' }} {!! $input_array['attribute'] !!}>
            @else
                <input type="checkbox" class="form-check-input" id="{{ $input_array['input_name'] . $key }}"  name="{{ $input_array['input_name'] }}[]" value="{{ $key }}" {!! $input_array['attribute'] !!}{{ $input_array['input_value'] ?? '' == $key ? ' checked' : '' }}>
            @endif
            <label class="form-check-label" for="{{ $input_array['input_name'] . $key }}">{{ $element }}</label>
        </div>
    @endforeach
</div>
@endcomponent