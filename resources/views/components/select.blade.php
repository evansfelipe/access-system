{{-- Component that shows a select with the Bootstrap 4 style.
     + Required variables that must be sent when calling this component:
        $name:  an unique string that indentifies this select against others components.
        $label: text to be shown above the select.
     + Optional variables:
        $required: indicates if the select is required or not. The default is false.
--}}
<div class="form-group">
    {{-- Shows the label for this select. --}}
@if(isset($label) && $label != "_blank")
        <label for="{{ $name }}">
                {{ $label }}
                @if(isset($required) && $required)
                *
                @endif
                :
        </label>
    @elseif($label == "_blank")
        <label for="{{ $name }}">&nbsp;</label>
        @endif
    {{-- Select with bootstrap class. --}}
    <select class="form-control"
            {{-- The dusk attribute is used for Dusk tests and the name attribute is used to retrieve the select's value on server. --}}
            dusk="{{ $name }}" name="{{ $name }}"
            {{-- The select is required by default. If a "required" variable is sent, and its value is false, then removes the required attribute --}}
            {{ isset($required) && $required ? 'required' : '' }}
    >
    {{-- Shows each option for the select. --}}
    @foreach($options as $option)
        {{-- Gets the value of this option. --}}
        <option value="{{$option['value']}}"
                {{-- If there is a validation error on server and the sent value is the one of this option, then puts this option as selected.
                     If there isn't a validation error on server but there is a value passed as parameter, and this value is the same than the one
                     on this option, then puts this option as select. Otherwise, lets this option unselected. --}}
                @if((old($name)==$option['value']) || (isset($value) && $value==$option['value'])) selected @endif
                {{-- The input is not required by default. If a "required" variable is sent, and its value is true, then adds the required attribute --}}
                {{ isset($required) && $required ? 'required' : '' }}
        >
                {{-- Shows the text of this option. --}}
                {{$option['text']}}
        </option>
    @endforeach
    </select>
    {{-- If the server validation returns an error under the name of this input, then adds a span with the text of the error
        The dusk attribute on the span is used on tests to validate that the error had happened and the text is being displayed --}}
        @if($errors->has($name))
        <div dusk="{{ $name }}-is-invalid" role="alert" class="invalid-feedback text-justify">
            @foreach($errors->get($name) as $error)
                <strong>{{ $error }}</strong> <br>
            @endforeach
        </div>
    @endif
</div>