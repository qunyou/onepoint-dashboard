@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'include'))
    @include($input_array['include_path'])
@endcomponent