{{-- Select with bootstrap class. --}}
<select class="form-control {{ $errors->has($name) ? 'is-invalid' : '' }}"
        {{-- The dusk attribute is used for Dusk tests and the name attribute is used to retrieve the select's value on server. --}}
        dusk="{{ $name }}" name="{{ $name }}"
        {{-- The select is required by default. If a "required" variable is sent, and its value is false, then removes the required attribute --}}
        {{ isset($required) && $required ? 'required' : '' }}
        {{-- Gives a title for the select input, the same the placeholder has. --}}
        title=" {{ isset($placeholder) ? $placeholder : '' }} "
>
        {{-- Sets an option that the user can't select as placeholder. --}}
        @if(isset($placeholder)) <option value="" hidden> {{ $placeholder }} </option> @endif
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
{{-- If the server validation returns an error under the name of this select, then shows them --}}
@include('components/_partials/validation-errors', ['name' => $name])
