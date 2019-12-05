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
if (!isset($input_value)) {
    $input_value = '';
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
?>
@if ($hidden)
    <div style="display: none">
@endif
    @if (!$input_only)
        <div class="form-group{{ $error ? ' has-error' : '' }}{{ $width_class ? ' inline-item ' . $width_class : '' }}">
            <label for="{{ $input_name }}" class="col-sm-2 control-label">{{ $display_name }}</label>
            <div class="col-sm-10">
    @endif
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </span>
                <input type="text" data-plugin-timepicker data-plugin-options='{"showMeridian": false }'' class="form-control" name="{{ $input_name }}" value="{{ $input_value }}"  {!! $attribute !!}>
            </div>
            @if ($error)
                <p class="text-danger">
                    {!! $error !!}
                </p>
            @endif
            @if ($help)
                <p class="text-info">
                    {!! $help !!}
                </p>
            @endif
    @if (!$input_only)
            </div>
        </div>
    @endif
@if (isset($hidden) && $hidden)
    </div>
@endif
