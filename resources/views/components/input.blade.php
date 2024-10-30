@props(['label','type', 'name','required_mark'])

<div class="form-group mb-3">
    <label for="">{{ $label }}
        @if($required_mark)
            <span style="color: red"> * </span>
        @endif
    </label>
    <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror"
        {{ $attributes }}>
    @error($name)
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>
