{{-- The input will be of the type indicated on the "type" variable --}}
<input  type="{{ $type }}"
        {{-- The dusk attribute is used for Dusk tests and the name attribute is used to retrieve the input's value on server. --}}
        dusk="{{ $name }}" name="{{ $name }}" 
        {{-- If the server validation returns an error, then shows the sent data so that it is not necessary to re-write it.
                If there isn't a validation error and there is a value sent to show, then shows that value. Otherwise, shows an empty input. --}}
        value="{{ old($name) ? old($name) : (isset($value) ? $value : '') }}"
        {{-- If the server validation returns an error under the name of this input, then adds a class to show the input with a red border. --}}
        class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}"
        {{-- The input is not required by default. If a "required" variable is sent, and its value is true, then adds the required attribute --}}
        {{ isset($required) && $required ? 'required' : '' }}
        placeholder="{{ $placeholder or '' }}"
>
{{-- If the server validation returns an error under the name of this input, then shows them --}}
@include('components/_partials/validation-errors', ['name' => $name])
