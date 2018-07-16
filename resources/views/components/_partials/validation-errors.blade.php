{{-- Shows a list with the text of the errors. The dusk attribute on the span is used on tests to validate
     that the error had happened and the text is being displayed --}}
@if($errors->has($name))
    <div dusk="{{ $name }}-is-invalid" role="alert" class="invalid-feedback text-justify">
        @foreach($errors->get($name) as $error)
            <strong>{{ $error }}</strong> <br>
        @endforeach
    </div>
@endif
