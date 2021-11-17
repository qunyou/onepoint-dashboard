@if (!isset($formPresenter))
    @inject('formPresenter', 'Onepoint\Dashboard\Presenters\FormPresenter')
@endif

@foreach ($form_array as $item_key => $item_value)
    @php
        $input_value = $item_value['input_value'] ?? old($item_key, isset($form_value) ? optional($form_value)->{$item_key} : '');
        if (isset($item_value['base64_decode']) && $item_value['base64_decode']) {
            $input_value = base64_decode($input_value);
        }
    @endphp
    @include('dashboard::' . $item_value['input_type'], ['input_setting' => [
        'display_name' => $item_value['display_name'],
        'input_name' => $item_key,
        'input_value' => $input_value,
        'upload_path' => $item_value['upload_path'] ?? '',
        'file_name_display_value' => $item_value['file_name_display_value'] ?? '',
        'value_type' => $item_value['value_type'] ?? '',
        'option' => $item_value['option'] ?? [],
        'prepend_str' => $item_value['prepend_str'] ?? '',
        'item_key' => $item_value['item_key'] ?? [],
        'parent_key' => $item_value['parent_key'] ?? [],
        'attribute' => $item_value['attribute'] ?? [],
        'row_attribute' => $item_value['row_attribute'] ?? [],
        'hidden' => $item_value['hidden'] ?? [],
        'image_attribute' => $item_value['image_attribute'] ?? '',
        'help' => $item_value['help'] ?? '',
        'image_thumb' => $item_value['image_thumb'] ?? false,
        'multiple' => $item_value['multiple'] ?? false,
        'rows' => $item_value['rows'] ?? 20,
        'parameter' => $item_value['parameter'] ?? [],
        'formPresenter' => $formPresenter,
        'delete_url' => $item_value['delete_url'] ?? '',
        'include_path' => $item_value['include_path'] ?? '',
        'use_array_value' => $item_value['use_array_value'] ?? false,
        'required' => $item_value['required'] ?? false,
    ]])
@endforeach