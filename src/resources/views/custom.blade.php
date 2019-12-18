@inject('formPresenter','Onepoint\Dashboard\Presenters\FormPresenter')
@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'custom'))
    @if (is_array($input_array['input_value']))
        <ul>
            @foreach ($input_array['input_value'] as $element_key => $element)
                <li>{{ $element_key . ':' . $element }}</li>
            @endforeach
        </ul>
    @else
        {{ $input_array['input_value'] }}
    @endif
@endcomponent