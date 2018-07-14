{{-- Component that shows an input with the Bootstrap 4 style.
     + Required variables that must be sent when calling this component:
        $name:  an unique string that indentifies this input against others components.
        $type:  the html input type.
        $label: text to be shown above the input.
     + Optional variables:
        $required: indicates if the input is required or not. The default is false.
--}}
<div class="form-group">
    {{-- Shows the label for this input --}}
    @if(isset($label) && $label != "_blank")
        <label for="{{ $name }}">{{ $label }}:</label>
    @elseif($label == "_blank")
        <label for="{{ $name }}">&nbsp;</label>
    @endif
    {{-- The input will be of the type indicated on the "type" variable --}}
    <input  type="{{ $type }}"
            {{-- The dusk attribute is used for Dusk tests and the name attribute is used to retrieve the input's value on server. --}}
            dusk="{{ $name }}" name="{{ $name }}" 
            {{-- If the server validation returns an error, then shows the sent data so that it is not necessary to re-write it.
                 If there isn't a validation error and there is a value sent to show, then shows that value. Otherwise, shows an empty input. --}}
            value="{{ old($name) ? old($name) : (isset($value) ? $value : '') }}"
            {{-- If the server validation returns an error under the name of this input, then adds a class to show the input with a red border. --}}
            class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}"
            {{-- The input is required by default. If a "required" variable is sent, and its value is false, then removes the required attribute --}}
            {{ isset($required) && !$required ? '' : 'required' }}
    >
    {{-- If the server validation returns an error under the name of this input, then adds a span with the text of the error
         The dusk attribute on the span is used on tests to validate that the error had happened and the text is being displayed --}}
    @if($errors->has($name))
        <span dusk="{{ $name }}-is-invalid" role="alert" class="invalid-feedback">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
