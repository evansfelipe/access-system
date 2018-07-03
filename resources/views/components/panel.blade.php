<div class="{{ $size or 'col' }}">
    <div class="card">
        @isset($header)
            <div class="card-header">
                <big><b>{{ $header }}</b></big>
            </div>
        @endisset
        <div class="card-body">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="card-footer">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>