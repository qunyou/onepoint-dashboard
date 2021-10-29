@if ($hidden)
    <div style="display: none">
@endif
@if (!$input_only)
<div class="mb-4">
    <div class="input-group" {!! $row_attribute !!}>
        <label for="{{ $input_name ?? '' }}" class="input-group-text">{!! $display_name !!}</label>
@endif
{{ $slot }}
@if (!$input_only)
    </div>
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
</div>
@endif

@if ($hidden)
    </div>
@endif