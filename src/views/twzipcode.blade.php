<?php
if (!isset($help)) {
    $help = false;
}
if (!isset($error)) {
    $error = false;
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
@if (!$input_only)
        <div class="form-group{{ $error ? ' has-error' : '' }}{{ $width_class ? ' inline-item ' . $width_class : '' }}">
            <label for="{{ $input_name }}" class="col-sm-2 control-label">{{ $display_name }}</label>
            <div class="col-sm-10">
    @endif

        <div id="{{ $input_name }}_twzipcode" class="row" style="margin-bottom: 10px;">
            <div class="col-md-2">
                <div data-role="county"
                     data-name="{{ $prifix }}county"
                     data-value="{{ $input_value_county }}"
                     data-style="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div data-role="district"
                     data-name="{{ $prifix }}district"
                     data-value="{{ $input_value_district }}"
                     data-style="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div data-role="zipcode"
                     data-name="{{ $prifix }}zipcode"
                     data-value="{{ $input_value_zipcode }}"
                     data-style="form-control">
                </div>
            </div>
        </div>
        <input type="text" class="form-control" name="{{ $input_name }}" value="{{ $input_value }}" {!! $attribute !!}>
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
