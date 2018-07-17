<hr>
{{-- Street, apartment & cp --}}
<div class="form-row">
    {{-- Street & apartment --}}
    @formitem(['label' => 'Domicilio', 'col' => 'col-md-8'])
        {{-- Street --}}
        <div class="col-6">
            @input(['type' => 'text', 'name' => 'street', 'placeholder' => 'Dirección', 'value' => $model->street ?? null])@endinput
        </div>
        {{-- Apartment --}}
        <div class="col-6">
            @input(['type' => 'text', 'name' => 'apartment', 'placeholder' => 'Piso y departamento', 'value' => $model->apartment ?? null])@endinput
        </div>
    @endformitem
    {{-- CP --}}
    @formitem(['label' => 'Código Postal', 'col' => 'col-md-4'])
        <div class="col">
            @input(['type' => 'text', 'name' => 'cp', 'value' => $model->cp ?? null])@endinput
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
                'placeholder' => 'Seleccione país',
                'value' => $model->country ?? null,
                'options' => [['value' => 'Argentina', 'text' => 'Argentina']]
            ])
            @endselect
        </div>
    @endformitem
    {{-- Province / state --}}
    @formitem(['label' => 'Provincia / Estado', 'col' => 'col-md-4'])
        <div class="col">
            @select([
                'name' => 'province',
                'placeholder' => 'Seleccione provincia / estado',
                'value' => $model->province ?? null,
                'options' => [['value' => 'Buenos Aires', 'text' => 'Buenos Aires']]
            ])
            @endselect
        </div>
    @endformitem
    {{-- City --}}
    @formitem(['label' => 'Ciudad', 'col' => 'col-md-4'])
        <div class="col">
            @select([
                'name' => 'city',
                'placeholder' => 'Seleccione ciudad',
                'value' => $model->city ?? null,
                'options' => [['value' => 'Mar del Plata', 'text' => 'Mar del Plata']]
            ])
            @endselect
        </div>
    @endformitem
</div>