<div class="form-group">
    <label for="{{ $name }}">{{ $label }}:</label>
    <input  type="{{ $type }}" 
            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
            name="{{$name}}" value="{{old($name)}}" 
            required
    >
    @if($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>