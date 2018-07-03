<div class="form-group">
    <label for="{{ $name }}">{{ $label }}:</label>
    <select class="form-control" name="{{ $name }}">
        @foreach($options as $option)
            <option @if(old($name)==$option['value']) selected @endif 
                    value="{{$option['value']}}"
            >
                    {{$option['text']}}
            </option>
        @endforeach
    </select>
</div>