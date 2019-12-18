@foreach ($form_array as $item_key => $item_value)
    @include('dashboard::' . $item_value['input_type'], ['input_setting' => [
        'display_name' => $item_value['display_name'],
        'input_name' => $item_key,
        'input_value' => $item_value['input_value'] ?? old($item_key, optional($form_value)->{$item_key}),
        'upload_path' => $item_value['upload_path'] ?? '',
        'value_type' => $item_value['value_type'] ?? '',
        'option' => $item_value['option'] ?? [],
        'attribute' => $item_value['attribute'] ?? [],
        'image_attribute' => $item_value['image_attribute'] ?? '',
        'help' => $item_value['help'] ?? '',
        'image_thumb' => $item_value['image_thumb'] ?? false,
        'multiple' => $item_value['multiple'] ?? false,
        'rows' => $item_value['rows'] ?? 20,
    ]])
@endforeach