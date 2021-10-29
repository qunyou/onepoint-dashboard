@if ($hidden)
    <div style="display: none">
@endif
@if (!$input_only)
    <div class="form-group mb-4" {!! $row_attribute !!}>
        <label for="{{ $input_name ?? '' }}">{!! $display_name !!}</label>
@endif
{{ $slot }}
@if ($error)
    <p class="text-danger">
        {!! $error !!}
    </p>
@endif
@if ($help)
    <div class="mt-1">
        <small class="form-text">
            {!! $help !!}
        </small>
    </div>
@endif

{!! $suffix ?? '' !!}

@if (!$input_only)
    </div>
@endif
@if ($hidden)
    </div>
@endif