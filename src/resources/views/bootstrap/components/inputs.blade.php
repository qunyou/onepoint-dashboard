@if ($hidden)
    <div style="display: none">
@endif
@if (!$input_only)
    <div class="form-group" {!! $row_attribute !!}>
        {{-- class="{{ $header_grid_class }}" --}}
        <label for="{{ $input_name ?? '' }}">{!! $display_name !!}</label>
        {{-- <div class="{{ $input_grid_class }}"> --}}
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

{!! $suffix ?? '' !!}

@if (!$input_only)
        {{-- </div> --}}
    </div>
@endif
@if ($hidden)
    </div>
@endif