@if ($hidden)
    <div style="display: none">
@endif
@if (!$input_only)
    <div class="form-group row" {!! $row_attribute !!}>
        <label for="{{ $input_name ?? '' }}" class="{{ $header_grid_class }}">{!! $display_name !!}</label>
        <div class="{{ $input_grid_class }}">
@endif
{{ $slot }}
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
@if ($hidden)
    </div>
@endif