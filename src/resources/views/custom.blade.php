@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::' . config('backend.template') . '.components.inputs-group', $input_array = $formPresenter->setValue($input_setting, 'custom'))
    @if (is_array($input_array['input_value']))
        <ul>
            @foreach ($input_array['input_value'] as $element_key => $element)
                <li>{{ $element_key . ':' . $element }}</li>
            @endforeach
        </ul>
    @else
        {!! $input_array['input_value'] !!}
    @endif
@endcomponent