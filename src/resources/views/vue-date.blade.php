@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting))
    <vuejs-datepicker :language="zh" :format="customFormatter" :input-class="styleClass" :value="{{ $input_array['input_name'] }}" :name="'{{ $input_array['input_name'] }}'" :disabled-dates="state.disabledDates"></vuejs-datepicker>

    @error($input_array['input_name'])
        <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
@endcomponent