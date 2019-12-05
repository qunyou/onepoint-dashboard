<?php
if (!isset($help)) {
    $help = false;
}
if (!isset($error)) {
    $error = false;
}
if (!isset($hidden)) {
    $hidden = false;
}
if (!isset($input_only)) {
    $input_only = false;
}

// 寬度樣式名稱
if (!isset($width_class)) {
    $width_class = false;
}

// 是否使用 .form-control-static 樣式
if (!isset($form_control_static)) {
    $form_control_static = true;
}

// 其他表單屬性
if (isset($attribute)) {
    if (is_array($attribute)) {
        $attribute_str = '';
        foreach ($attribute as $attribute_key => $attribute_value) {
            $attribute_str .= ' ' . $attribute_key . '="' . $attribute_value . '"';
        }
        $attribute = $attribute_str;
    }
} else {
    $attribute = '';
}

// grid class
if (!isset($header_grid_class)) {
    $header_grid_class = 'col-sm-2 col-form-label';
}
if (!isset($input_grid_class)) {
    $input_grid_class = 'col-sm-10';
}

// form size
$header_grid_class .= ' col-form-label';
$input_size_class = 'styleClass';
if (isset($input_size)) {
    switch ($input_size) {
        case 'lg':
            $header_grid_class .= ' col-form-label-lg';
            $input_size_class = 'styleClassLg';
            break;
        case 'sm':
            $header_grid_class .= ' col-form-label-sm';
            $input_size_class = 'styleClassSm';
            break;
    }
}
?>
@if ($hidden)
    <div style="display: none">
@endif

@if (!$input_only)
    <div class="form-group row{{ $error ? ' has-error' : '' }}{{ $width_class ? ' inline-item ' . $width_class : '' }}">
        <label for="{{ $input_name }}" class="{{ $header_grid_class }}">{{ $display_name }}</label>
        <div class="{{ $input_grid_class }}">
@endif
            <vuejs-datepicker :language="zh" :input-class="{{ $input_size_class }}" :value="{{ $input_name }}" name="{{ $input_name }}" :format="customFormatter" {!! $attribute !!}></vuejs-datepicker>
            @if ($error)
                <p class="text-danger">{!! $error !!}</p>
            @endif

            @if ($help)
                <p class="text-info">{!! $help !!}</p>
            @endif

@if (!$input_only)
        </div>
    </div>
@endif

@if ($hidden)
    </div>
@endif
