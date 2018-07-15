<hr>
<button dusk="{{ $id or 'submitbutton'}}"
        id="{{ $id or 'submitbutton'}}"
        type="submit"
        class="btn btn-{{ $color or 'default' }}
               btn-sm
               text-uppercase
               font-weight-bold
               {{ isset($floatright) && !$floatright ? '' : 'float-right' }}"
>
        {{ $slot }}
</button>