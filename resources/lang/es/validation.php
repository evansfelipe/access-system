<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'active_url'           => 'El campo :attribute no es una URL válida.',
    'after'                => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo :attribute sólo puede contener letras.',
    'alpha_dash'           => 'El campo :attribute sólo puede contener letras, números y guiones (a-z, 0-9, -_).',
    'alpha_num'            => 'El campo :attribute sólo puede contener letras y números.',
    'array'                => 'El campo :attribute debe ser un array.',
    'before'               => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'numeric' => 'El campo :attribute debe ser un valor entre :min y :max.',
        'file'    => 'El archivo :attribute debe pesar entre :min y :max kilobytes.',
        'string'  => 'El campo :attribute debe contener entre :min y :max caracteres.',
        'array'   => 'El campo :attribute debe contener entre :min y :max elementos.',
    ],
    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed'            => 'El campo confirmación de :attribute no coincide.',
    'date'                 => 'El campo :attribute no corresponde con una fecha válida.',
    'date_format'          => 'El campo :attribute no corresponde con el formato de fecha :format.',
    'different'            => 'Los campos :attribute y :other deben ser diferentes.',
    'digits'               => 'El campo :attribute debe ser un número de :digits dígitos.',
    'digits_between'       => 'El campo :attribute debe contener entre :min y :max dígitos.',
    'dimensions'           => 'El campo :attribute tiene dimensiones inválidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'email'                => 'El campo :attribute debe ser una dirección de correo válida.',
    'exists'               => 'El campo :attribute seleccionado no existe.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute debe tener algún valor.',
    'image'                => 'El campo :attribute debe ser una imagen.',
    'in'                   => 'El campo :attribute es inválido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => 'El campo :attribute debe ser un número entero.',
    'ip'                   => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4'                 => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo :attribute debe ser una cadena de texto JSON válida.',
    'max'                  => [
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
        'file'    => 'El archivo :attribute no debe pesar más de :max kilobytes.',
        'string'  => 'El campo :attribute no debe contener más de :max caracteres.',
        'array'   => 'El campo :attribute no debe contener más de :max.',
    ],
    'mimes'                => 'El campo :attribute debe ser un archivo de tipo :values.',
    'mimetypes'            => 'El campo :attribute debe ser un archivo de tipo :values.',
    'min'                  => [
        'numeric' => 'El campo :attribute debe tener al menos :min.',
        'file'    => 'El archivo :attribute debe pesar al menos :min kilobytes.',
        'string'  => 'El campo :attribute debe contener al menos :min caracteres.',
        'array'   => 'El campo :attribute debe contener al menos :min elementos.',
    ],
    'not_in'               => 'El campo :attribute seleccionado es inválido.',
    'not_regex'            => 'El formato del campo :attribute es inválido.',
	'numeric'              => 'El campo :attribute debe ser un número.',
    'present'              => 'El campo :attribute debe estar presente.',
    'regex'                => 'El formato del campo :attribute es inválido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_if'          => 'El campo :attribute es obligatorio cuando el campo :other es :value.',
    'required_unless'      => 'El campo :attribute es requerido a menos que :other se encuentre en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los campos :values está presente.',
    'same'                 => 'Los campos :attribute y :other deben coincidir.',
    'size'                 => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file'    => 'El archivo :attribute debe pesar :size kilobytes.',
        'string'  => 'El campo :attribute debe contener :size caracteres.',
        'array'   => 'El campo :attribute debe contener :size elementos.',
    ],
    'string'               => 'El campo :attribute debe ser una cadena de caracteres.',
    'timezone'             => 'El campo :attribute debe contener una zona válida.',
    'unique'               => 'El valor del campo :attribute ya está en uso.',
    'uploaded'             => 'El campo :attribute falló al subir.',
    'url'                  => 'El formato del campo :attribute es inválido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        // Person's personal information
        'picture'                   => 'Foto',
        'last_name'                 => 'Apellido',
        'name'                      => 'Nombre',
        'document_type'             => 'Tipo de documento',
        'document_number'           => 'Número de documento',
        'cuil'                      => 'CUIL / CUIT',
        'cuit'                      => 'CUIL / CUIT',
        'birthday'                  => 'Fecha de nacimiento',
        'sex'                       => 'Género',
        'blood_type'                => 'Grupo y factor sanguíneo',
        'pna'                       => 'Prontuario PNA',
        'email'                     => 'Email',
        'phone'                     => 'Teléfono',
        'home_phone'                => 'Teléfono fijo',
        'mobile_phone'              => 'Teléfono móvil',
        'fax'                       => 'FAX',
        'street'                    => 'Calle y número',
        'apartment'                 => 'Departamento',
        'cp'                        => 'Código postal',
        'country'                   => 'País',
        'province'                  => 'Provincia',
        'city'                      => 'Ciudad',
        'risk'                      => 'Riesgo',
        'homeland'                  => 'Nacionalidad',
        'register_number'           => 'Nº Legajo',
        // Person's working information
        'company_id'                => 'Empresa',
        'activity_id'               => 'Actividad',
        'art'                       => 'ART',
        'pbip'                      => 'Vencimiento PBIP',
        // Person's first card
        'number'                    => 'Número',
        'from'                      => 'Desde',
        'until'                     => 'Hasta',
        // Jobs 
        'jobs.*.company_id'         => 'Empresa',
        'jobs.*.activity_id'        => 'Actividad',
        'jobs.*.subactivities'      => 'Subactividad/es',
        'jobs.*.subactivities.*'    => 'Subactividad/es',
        'jobs.*.art_company'        => 'Empresa ART',
        'jobs.*.art_number'         => 'Número ART',
        'jobs.*.cards.*.number'     => 'Número de la tarjeta',
        'jobs.*.cards.*.from'       => 'Valido desde',
        'jobs.*.cards.*.until'      => 'Valido hasta',
        // Company's general information
        'business_name'             => 'Razón social',
        'area'                      => 'Rubro',
        'cuit'                      => 'CUIT',
        'expiration'                => 'Vencimiento',
        'web'                       => 'Página web',
        // Company's groups
        'groups.*.gate_id'          => 'Entrada',
        'groups.*.name'             => 'Nombre',
        'groups.*.start'            => 'Comienzo',
        'groups.*.end'              => 'Finalización',
        // Vehicle's general information
        'type'                      => 'Tipo',
        'type_id'                   => 'Tipo',
        'plate'                     => 'Patente',
        'owner'                     => 'Titular',
        'brand'                     => 'Marca',
        'model'                     => 'Modelo',
        'year'                      => 'Año',
        'colour'                    => 'Color',
        'insurance'                 => 'Seguro',
        'vtv'                       => 'VTV',
        // Vehicle's general information
        'series_number'             => 'Número de serie',
        'format'                    => 'Formato',
        'size'                      => 'Tamaño',
        // Groups
        'gate_id'                   => 'Entrada',
        'start'                     => 'Comienzo',
        'end'                       => 'Finalización',
    ],

];
