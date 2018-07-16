<div class="{{ $col or 'col' }}">
    <div class="form-group">
        <div class="form-row">
            <div class="col">
                <label>{{ $label }}:</label>
            </div>
        </div>
        <div class="form-row">
            {{ $slot }}
        </div>
    </div>
</div>