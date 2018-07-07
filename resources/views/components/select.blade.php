{{-- Component that shows a select with the Bootstrap 4 style.
     + Required variables that must be sent when calling this component:
        $name:  an unique string that indentifies this select against others components.
        $label: text to be shown above the select.
     + Optional variables:
        $required: indicates if the select is required or not. The default is false.
--}}
<div class="form-group">
    {{-- Shows the label for this select. --}}
    <label for="{{ $name }}">{{ $label }}:</label>
    {{-- Select with bootstrap class. --}}
    <select class="form-control"
            {{-- The dusk attribute is used for Dusk tests and the name attribute is used to retrieve the select's value on server. --}}
            dusk="{{ $name }}" name="{{ $name }}"
            {{-- The select is required by default. If a "required" variable is sent, and its value is false, then removes the required attribute --}}
            {{ isset($required) && !$required ? '' : 'required' }}
    >
    {{-- Shows each option for the select. --}}
    @foreach($options as $option)
        {{-- Gets the value of this option. --}}
        <option value="{{$option['value']}}"
                {{-- If there is a validation error on server and the sent value is the one of this option, then puts this option as selected.
                     If there isn't a validation error on server but there is a value passed as parameter, and this value is the same than the one
                     on this option, then puts this option as select. Otherwise, lets this option unselected. --}}
                @if((old($name)==$option['value']) || (isset($value) && $value==$option['value'])) selected @endif 
        >
                {{-- Shows the text of this option. --}}
                {{$option['text']}}
        </option>
    @endforeach
    </select>
</div>