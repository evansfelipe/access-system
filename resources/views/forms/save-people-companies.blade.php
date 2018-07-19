@csrf
{{-- Company --}}
<div class="form-row">
    @formitem(['label' => 'Empresa'])
        <div class="col-md-6">
            @select([
                'name' => 'company_id',
                'placeholder' => 'Seleccione empresa',
                'value' => $people_companies->company_id ?? null,
                'required' => true,
                'options' => $companies_data
            ])
            @endselect
        </div>
        <div class="col-md-1 text-center">
            <strong>o</strong>
        </div>
        <div class="col-md-5">
            @formbutton Cree nueva empresa @endformbutton
        </div>
        <div class="col">
            <div class="form-check">
                <input type="checkbox" class="form-check-input">
                <label class="form-check-label">Monotributista</label>
            </div>
        </div>
    @endformitem                     
</div>
{{-- Activity --}}
<div class="form-row">
    @formitem(['label' => 'Actividad / Categoría'])
        <div class="col-md-6">
            @select([
                'name' => 'activity_id',
                'placeholder' => 'Seleccione actividad',
                'value' => $people_companies->activity_id ?? null,
                'required' => true,
                'options' => [
                    ['value' => '1', 'text' => 'Pesquero'],
                    ['value' => '2', 'text' => 'Control de calidad'],
                ]
            ])
            @endselect
        </div>
        <div class="col-md-1 text-center">
            <strong>o</strong>
        </div>
        <div class="col-md-5">
            @formbutton Cree nueva actividad @endformbutton
        </div>
    @endformitem
</div>
{{-- ART --}}
<div class="form-row">
    @formitem(['label' => 'ART', 'col' => 'col-md-6'])
        <div class="col">
            @input(['type' => 'text', 'name' => 'art', 'value' => $people_companies->art ?? null, 'required' => true])@endinput                        
        </div>
    @endformitem                    
</div>
{{-- PBIP --}}
<div class="form-row">
    @formitem(['label' => 'Vencimiento PBIP', 'col' => 'col-md-6'])
        <div class="col">
            @input(['type' => 'date', 'name' => 'pbip', 'value' => $people_companies->pbip ?? null])@endinput                        
        </div>
    @endformitem
</div>