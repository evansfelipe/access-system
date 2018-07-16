<hr>
{{-- Street, apartment & cp --}}
<div class="form-row">
    {{-- Street & apartment --}}
    @formitem(['label' => 'Domicilio', 'col' => 'col-md-8'])
        {{-- Street --}}
        <div class="col-6">
            @input(['type' => 'text', 'name' => 'street', 'placeholder' => 'Dirección'])@endinput
        </div>
        {{-- Apartment --}}
        <div class="col-6">
            @input(['type' => 'text', 'name' => 'apartment', 'placeholder' => 'Piso y departamento'])@endinput
        </div>
    @endformitem
    {{-- CP --}}
    @formitem(['label' => 'Código Postal', 'col' => 'col-md-4'])
        <div class="col">
            @input(['type' => 'text', 'name' => 'cp'])@endinput
        </div>
    @endformitem
</div>
{{-- Country, province/state & city --}}
<div class="form-row">
    {{-- Country --}}
    @formitem(['label' => 'País', 'col' => 'col-md-4'])
        <div class="col">
            @select([
                'name' => 'country',
                'options' => [['value' => 'arg', 'text' => 'Argentina']]
            ])
            @endselect
        </div>
    @endformitem
    {{-- Province / state --}}
    @formitem(['label' => 'Provincia / Estado', 'col' => 'col-md-4'])
        <div class="col">
            @select([
                'name' => 'province',
                'options' => [['value' => 'baires', 'text' => 'Buenos Aires']]
            ])
            @endselect
        </div>
    @endformitem
    {{-- City --}}
    @formitem(['label' => 'Ciudad', 'col' => 'col-md-4'])
        <div class="col">
            @select([
                'name' => 'city',
                'options' => [['value' => 'mdp', 'text' => 'Mar del Plata']]
            ])
            @endselect
        </div>
    @endformitem
</div>