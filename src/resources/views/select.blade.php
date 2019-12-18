@inject('formPresenter','Onepoint\Dashboard\Presenters\FormPresenter')
@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'select'))
    <select name="{{ $input_array['input_name'] }}" class="form-control {{ $input_array['input_size_class'] }}  @error($input_array['input_name']) is-invalid @enderror" {!! $input_array['attribute'] !!}>
        {!! $input_array['prepend_str'] !!}
        {!! $formPresenter->setOption($input_array['option'], $input_array['use_array_value'], $input_array['input_value']) !!}
        {!! $input_array['depend_str'] !!}
    </select>

    @error($input_array['input_name'])
        <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
@endcomponent