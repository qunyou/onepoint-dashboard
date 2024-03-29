@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs', $input_array = $formPresenter->setValue($input_setting, 'textarea'))
    @if($input_array['required'])
        <span class="input-group-text">@lang('dashboard::backend.必填')</span>
    @endif
    <textarea class="form-control {{ $input_array['input_size_class'] }}  @error($input_array['input_name']) is-invalid @enderror" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}" {!! $input_array['rows'] !!} {!! $input_array['attribute'] !!}>{{ $input_array['input_value'] }}</textarea>
    @error($input_array['input_name'])
        <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
@endcomponent