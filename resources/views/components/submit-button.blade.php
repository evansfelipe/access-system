<button dusk="{{ $id or 'submitbutton'}}"
        id="{{ $id or 'submitbutton'}}"
        type="submit"
        class="btn btn-{{ $color or 'outline-success' }}
               btn-sm"
>
        {{ $slot }}
</button>