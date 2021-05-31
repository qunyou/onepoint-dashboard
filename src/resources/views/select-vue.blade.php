@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@component('dashboard::components.inputs', $input_array = $formPresenter->setValue($input_setting, 'select-vue'))
    <select name="{{ $input_array['input_name'] }}" class="form-control {{ $input_array['input_size_class'] }}  @error($input_array['input_name']) is-invalid @enderror" {!! $input_array['attribute'] !!} v-model="{{ $input_array['input_name'] }}">
        {!! $input_array['prepend_str'] !!}
        @if (empty($input_array['compare']))
            @if (!empty($input_array['item_key']))
                <option :value="index" v-for="(item, index) in {{ $input_array['input_name'] . '_option' }}" v-if="{{ $input_array['parent_key'] }} == {{ 'item.' . $input_array['parent_key'] }}">
                    {!! '@{{ item.' . $input_array['item_key'] . ' }}' !!}
                </option>
            @else
                <option :value="index" v-for="(item, index) in {{ $input_array['input_name'] . '_option' }}">@{{ item }}</option>
            @endif
        @else
            <template v-for="(item, index) in {{ $input_array['input_name'] . '_option' }}" v-if="{{ $input_array['compare'] }} == index">
                <option :value="item_option" v-for="(item_option, item_index) in item">@{{ item_option }}</option>
            </template>
        @endif
        {!! $input_array['depend_str'] !!}
    </select>

    @error($input_array['input_name'])
        <div class="alert alert-danger mt-1">{{ $message }}</div>
    @enderror
@endcomponent

@section('vuejs_data')
    @parent
    {{ $input_array['input_name'] }}: '{{ array_key_first($input_array['option']) }}',
    {{ $input_array['input_name'] . '_option' }}: {!! json_encode($input_array['option']) !!},
@endsection

@section('vuejs_watchs')
    @parent
    @if (!empty($input_array['parent_key']))
        {{ $input_array['parent_key'] }}: function (val) {
            this.{{ $input_array['input_name'] }} = '';
        }
    @endif
@endsection

{{-- 
@section('vuejs_data')
    @parent
    @if (empty($input_array['compare']))
        {{ $input_array['input_name'] }}: '{{ $input_array['option'][array_key_first($input_array['option'])] }}',
        {{ $input_array['input_name'] . '_option' }}: {!! json_encode($input_array['option']) !!},
    @else
        @php
            $option_array = $formPresenter->setVueOption($input_array['option'], $input_array['compare']);
        @endphp
        {{ $input_array['input_name'] }}: '{{ $option_array[array_key_first($option_array)] }}',
        {{ $input_array['input_name'] . '_option' }}: {!! json_encode($option_array) !!},
        {{ $input_array['input_name'] . '_option' }}: {!! json_encode($input_array['option']) !!},
    @endif
@endsection
--}}