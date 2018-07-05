<div class="form-group">
    <label for="{{ $name }}">{{ $label }}:</label>
    <input  dusk="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}" 
            class="form-control 
                   {{ $errors->has($name) ? 'is-invalid' : '' }}"
            value="{{old($name)}}"
            {{ isset($required) && !$required ? '' : 'required' }}
    >
    @if($errors->has($name))
        <span dusk="{{ $name }}-is-invalid"
              class="invalid-feedback"
              role="alert"
        >
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>