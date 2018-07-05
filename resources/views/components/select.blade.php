<div class="form-group">
    <label for="{{ $name }}">{{ $label }}:</label>
    <select dusk="{{ $name }}"
            name="{{ $name }}"
            class="form-control"
    >
        @foreach($options as $option)
            <option @if((old($name)==$option['value']) || (isset($value) && $value==$option['value'])) selected @endif 
                    value="{{$option['value']}}"
            >
                    {{$option['text']}}
            </option>
        @endforeach
    </select>
</div>