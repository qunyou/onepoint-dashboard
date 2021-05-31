@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@section('js')
    @parent
    <script src="{{ $path_presenter::backend_assets('js/vuejs-datepicker/vuejs-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ $path_presenter::backend_assets('js/vuejs-datepicker/zh.js') }}" type="text/javascript"></script>
@endsection

@section('vuejs_data')
    zh: vdp_translation_zh.js,
    @foreach ($input_key_value_array as $input_name => $input_value)
        {{ $input_name }}: {!! $input_value !!},
    @endforeach
    styleClass: 'form-control form-control',
    styleClassLg: 'form-control form-control-lg',
    styleClassSm: 'form-control form-control-sm',
@endsection

@section('vuejs_components')
    vuejsDatepicker
@endsection

@section('vuejs_methods')
    @parent
    customFormatter(inputDate) {
        return moment(inputDate).format('YYYY-MM-DD');
    },
@endsection
