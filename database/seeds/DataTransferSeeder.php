<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DataTransferSeeder extends Seeder
{
    // art config:cache

    public function removeAccentMarks($string)
    {
        $string = str_replace(['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],    ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'],  $string);
        $string = str_replace(['é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'],         ['e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'],       $string);
        $string = str_replace(['í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'],         ['i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'],       $string);
        $string = str_replace(['ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'],         ['o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'],       $string);
        $string = str_replace(['ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'],         ['u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'],       $string);
        $string = str_replace(['ñ', 'Ñ', 'ç', 'Ç'],                             ['n', 'N', 'c', 'C'],                           $string);
        return $string;
    }

    public function _($string, $def = null)
    {
        if(empty($string)) {
            return $def;
        }

        $aux = trim($string);
        $aux = strtoupper($aux);
        $aux = preg_replace('!\s+!', ' ', $aux);
        $aux = $this->removeAccentMarks($aux);

        return strlen($aux) > 0 ? $aux : $def;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $sqlsrv = DB::connection('sqlsrv');

        foreach(['residencies', 'companies', 'vehicles', 'vehicle_types', 'people', 'activities', 'company_people', 'cards', 'person_vehicle'] as $table) {
            DB::table($table)->truncate();
        }

        /**
         * Obtiene los id de las empresas que serán migradas. Para que una empresa sea migrada debe:
         *      - Tener CUIT distinto de null y distinto de vacio.
         *      - Su CUIT debe tener una longitud menor a 15 caracteres.
         *      - Si un CUIT se encuentra repetido, solo será migrada la primer empresa con dicho CUIT.
         */
        $id_empresas_migradas = $sqlsrv ->table('dbo.Empresa')
                                        ->select(DB::raw('MIN(id_empresa) as id_empresa'))
                                        ->whereNotNull('cuit')
                                        ->where('cuit', '<>', '')
                                        ->whereRaw('LEN(cuit) <= 15')
                                        ->groupBy('cuit')
                                        ->get()->pluck('id_empresa');
        /**
         * Obtiene los objetos de las empresas que serán migradas.
         */
        $empresas = $sqlsrv ->table('dbo.Empresa')->whereIn('id_empresa', $id_empresas_migradas)->get();

        /**
         * Array donde se asociara cada CUIT de empresa con el id en la nueva base de datos, para luego poder
         * crear las relaciones donde intervengan las empresas.
         */
        $company_migation_id = [];

        /**
         * Itera y guarda cada empresa, asi como su residencia.
         */
        foreach ($empresas as $empresa) {
            // Crea y guarda la residencia de esta empresa.
            $residency = new \App\Residency([
                'cp'        => strlen($empresa->codigo_postal) < 10 ? $this->_($empresa->codigo_postal) : null,
                'street'    => $this->_($empresa->domicilio,        'XXXX'),
                'country'   => $this->_($empresa->pais,             'XXXX'),
                'province'  => $this->_($empresa->provincia_estado, 'XXXX'),
                'city'      => $this->_($empresa->ciudad,           'XXXX')
            ]);
            $residency->save();

            // Crea el contacto de esta empresa.
            $contact = json_encode([
                'phone' => $this->_($empresa->telefono),
                'email' => $this->_($empresa->mail),
                'web'   => $this->_($empresa->web),
                'fax'   => $this->_($empresa->fax),
            ]);

            // Crea y guarda la empresa (junto a su contacto).
            $company = new \App\Company([
                'business_name' => $this->_($empresa->razon_social,     $this->_($empresa->nombre_fantasia, 'XXXX')),
                'name'          => $this->_($empresa->nombre_fantasia,  $this->_($empresa->razon_social,    'XXXX')),
                'area'          => $this->_($empresa->actividad, 'XXXX'),
                'cuit'          => $empresa->cuit,
                'residency_id'  => $residency->id,
                'expiration'    => Carbon::now(),
                'contact'       => $contact
            ]);
            $company->save();

            // Agrega el cuit y el id de esta empresa en el array para tal fin.
            $company_migation_id[$empresa->cuit] = $company->id;
        }

        /**
         * Obtiene todas las relaciones entre empresas y vehiculos. Si un vehiculo esta relacionado a más de una empresa,
         * se guardará solamente la primer relación del mismo. Todos los vehículos aquí no presentes no serán migrados, ya que
         * para que un vehículo exista es necesario que este asociado a una empresa.
         */
        $vehiculo_empresa = $sqlsrv ->table('dbo.vehiculo_empresa')
                                    ->select('id_vehiculo', DB::raw('MIN(id_empresa) as id_empresa'))
                                    ->groupBy('id_vehiculo')
                                    ->get();

        $vehicles_migration_id = [];
        /**
         * Guarda cada uno de los vehículos que estan relacionados a una empresa.
         */    
        foreach ($vehiculo_empresa as $row) {
            /**
             * Utilizando el id de la empresa con la cual el vehículo esta relacionada,
             * obtiene el CUIT de la misma.
             */
            $empresa = $sqlsrv  ->table('dbo.Empresa')
                                ->select('cuit')
                                ->where('id_empresa', $row->id_empresa)
                                ->first();
            /**
             * Utilizando el id del vehículo, obtiene todos los datos del mismo.
             */
            $vehiculo = $sqlsrv ->table('dbo.Vehiculo')
                                ->where('id_vehiculo', $row->id_vehiculo)
                                ->first();

            /**
             * El vehículo se migrara si existe la empresa al cual esta relacionado,
             * si el vehículo existe y ademas se migró una empresa con el mismo CUIT
             * de la empresa a la cual esta relacionado.
             */
            if( !empty($empresa) && 
                !empty($vehiculo) && 
                array_key_exists($empresa->cuit, $company_migation_id)) {
                /**
                 * Crea y guarda el tipo de vehículo. Si el tipo ya existe, entonces
                 * no lo guarda y se queda con el id del tipo ya existente.
                 */
                $type = \App\VehicleType::firstOrCreate(
                    ['type' => $this->_($vehiculo->tipo_personalizado, 'XXXX')],
                    ['allows_container' => false]
                );

                /**
                 * Guarda el vehículo, asociandolo a su tipo.
                 */
                $vehicle = new \App\Vehicle([
                    'company_id'    => $company_migation_id[$empresa->cuit],
                    'type_id'       => $type->id,
                    'owner'         => $this->_($vehiculo->titular, 'XXXX'),
                    'plate'         => $this->_($vehiculo->patente),
                    'brand'         => $this->_($vehiculo->marca,   'XXXX'),
                    'model'         => $this->_($vehiculo->modelo,  'XXXX'),
                    'colour'        => $this->_($vehiculo->color),
                    'year'          => $vehiculo->anio > '1940' ? $vehiculo->anio : '1940',
                    'insurance'     => Carbon::now()
                ]);
                $vehicle->save();

                $vehicles_migration_id[$vehiculo->id_vehiculo] = $vehicle->id;
            }
        }

        /**
         * Obtiene todas las personas que tengan un nombre y un apellido.
         */
        $personas = $sqlsrv ->table('dbo.Usuario')
                            ->whereNotNull('nombre')
                            ->where('nombre', '<>', '')
                            ->whereNotNull('apellido')
                            ->where('apellido', '<>', '')
                            ->get();

        foreach ($personas as $persona) {
            // Crea y guarda la residencia de esta persona.
            $residency = new \App\Residency([
                'cp'        => strlen($persona->codigo_postal) < 10 ? $this->_($persona->codigo_postal) : null,
                'street'    => $this->_($persona->direccion),
                'country'   => $this->_($persona->pais),
                'province'  => $this->_($persona->provincia),
                'city'      => $this->_($persona->ciudad)
            ]);
            $residency->save();
            
            // Crea el contacto de esta persona.
            $contact = json_encode([
                'fax'          => $this->_($persona->fax),
                'mobile_phone' => $this->_($persona->movil),
                'email'        => $this->_($persona->email),
                'home_phone'   => $this->_($persona->telefono),
            ]);

            // Crea la documentación requerida de esta persona.
            $required_documentation = json_encode([
                'acc_pers'          => false,
                'art_file'          => false,
                'boarding_card'     => false,
                'boarding_passbook' => false,
                'company_note'      => false,
                'dni_copy'          => false,
                'driver_license'    => false,
                'health_notebook'   => false,
                'pbip_file'         => false,
                'pna_file'          => false,
            ]);
            
            // Guarda esta persona junto a su contacto y documentación requerida.
            $person = new \App\Person([
                'enabled'                   => $persona->baja === null ? true : false,
                'last_name'                 => $this->_($persona->apellido),
                'name'                      => $this->_($persona->nombre),
                'document_type'             => 0,
                'document_number'           => strlen($this->_($persona->documento, 'XXXXXXXX')) <= 15 ? $this->_($persona->documento, 'XXXXXXXX') : 'XXXXXXXX',
                'birthday'                  => $persona->fecha_nacimiento ? new Carbon($persona->fecha_nacimiento) : null,
                'sex'                       => $persona->genero_masculino ? 'M' : 'F',
                'risk'                      => $persona->riesgo,
                'register_number'           => $persona->legajo_interno,
                'pna'                       => $persona->prontuario,
                'contact'                   => $contact,
                'required_documentation'    => $required_documentation,
                'residency_id'              => $residency->id
            ]);
            $person->save();

            /**
             * Crea y guarda la actividad de la persona. Si la actividad ya existe, entonces
             * no la guarda y se queda con el id de la existente.
             */
            $empresa_tarea = $persona->empresa_tarea;
            $empresa_tarea = str_replace('-',               ' ',            $empresa_tarea);
            $empresa_tarea = str_replace('.',               '',             $empresa_tarea);
            $empresa_tarea = str_replace('/',               '',             $empresa_tarea);
            $empresa_tarea = str_replace('.',               '',             $empresa_tarea);
            $empresa_tarea = str_replace('PBIP',            'P.B.I.P.',     $empresa_tarea);
            $empresa_tarea = str_replace('ESTIBADO ',       'ESTIBADOR ',   $empresa_tarea);
            $empresa_tarea = str_replace('PROFECIONAL ',    'PROFESIONAL ', $empresa_tarea);
            $empresa_tarea = preg_replace('/^[^A-Za-z]+/',  '',             $empresa_tarea);
            $empresa_tarea = $this->_($empresa_tarea, 'XXXX');
            $activity = \App\Activity::firstOrCreate(['name' => $empresa_tarea]);

            /**
             * Utilizando el id de la empresa con la cual la persona esta relacionada,
             * obtiene el CUIT de la misma.
             */
            $empresa = $sqlsrv  ->table('dbo.Empresa')
                                ->select('cuit')
                                ->where('id_empresa', $persona->id_empresa)
                                ->first();
            
            $company_id = !empty($empresa) && array_key_exists($empresa->cuit, $company_migation_id) ? $company_migation_id[$empresa->cuit] : null;

            $job = new \App\PersonCompany([
                'person_id'     => $person->id,
                'company_id'    => $company_id,
                'activity_id'   => $activity->id,
                'subactivities' => json_encode([]),
                'art_company'   => 'XXXXX',
                'art_number'    => 0,
            ]);
            $job->save();

            $pines = $sqlsrv->table('dbo.Pin_por_usuario as PU')
                            ->join('dbo.Usuario as U', 'U.id_usuario', '=', 'PU.id_usuario')
                            ->join('dbo.Pin as P', 'P.id_pin', '=', 'PU.id_pin')
                            ->where('PU.id_usuario', $persona->id_usuario)
                            ->whereNotNull('P.numero')
                            ->where('P.numero', '<>', '')
                            ->select('P.fc as fc', 'P.numero as numero', 'P.id_grupo as id_grupo', 'U.validez_desde as validez_desde', 'U.validez_hasta as validez_hasta')
                            ->get();

            foreach ($pines as $pin) {
                $card = new \App\Card([
                    'fc'                => $pin->fc,
                    'number'            => $pin->numero,
                    'person_company_id' => $job->id,
                    'active'            => $pin->id_grupo === null ? false : true,
                    'from'              => new Carbon($pin->validez_desde),
                    'until'             => new Carbon($pin->validez_hasta),
                ]);
                $card->save();
            }
            
            $vehiculo_persona = $sqlsrv ->table('dbo.vehiculo_usuario')
                                        ->where('id_usuario', $persona->id_usuario)
                                        ->select('id_vehiculo')
                                        ->get();

            foreach ($vehiculo_persona as $row) {
                if(array_key_exists($row->id_vehiculo, $vehicles_migration_id)) {
                    $person_vehicle = new \App\PersonVehicle([
                        'person_id'  => $person->id,
                        'vehicle_id' => $vehicles_migration_id[$row->id_vehiculo]
                    ]);
                    $person_vehicle->save();
                }
            }
        }
    }
}
