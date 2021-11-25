@if ($hidden)
    <div style="display: none">
@endif
@if (!$input_only)
<div class="mb-4">
    <div class="input-group" {!! $row_attribute !!}>
        <span for="{{ $input_name ?? '' }}" class="input-group-text">{!! $display_name !!}</span>
@endif
{{ $slot }}
@if (!$input_only)
    </div>
    {!! $error_slot ?? '' !!}
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