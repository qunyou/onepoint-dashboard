@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs-group', $input_array = $formPresenter->setValue($input_setting, 'file'))
    <input type="file" class="{{ $input_array['input_size_class'] }}" id="{{ $input_array['input_name'] }}" name="{{ $input_array['input_name'] }}{{ $input_array['multiple'] ? '[]' : '' }}" {!! $input_array['attribute'] !!} {{ $input_array['multiple'] ? 'multiple' : '' }} {!! $input_array['accept'] !!}>
    @if (!blank($input_array['image_string']))
        @slot('suffix')
            <div>
                {!! $input_array['prepend_str'] !!}
                {!! $input_array['image_string'] !!}
                {!! $input_array['depend_str'] !!}
                <a class="btn btn-primary" href="{{ $input_array['delete_url'] }}">
                    @lang('dashboard::backend.刪除')
                </a>
            </div>
        @endslot
    @endif
@endcomponent

