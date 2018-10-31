<?php

class Person extends BaseClass
{
    public function __construct($mysql, $mssql, $values) {
        parent::__construct($mysql, $values);
        $this->mssql = $mssql;
    }

    public function save() {
        // First, we have to save the basic person information.
        $contact = json_encode([
            'fax'          => $this->scape($this->values['fax']),
            'email'        => $this->scape($this->values['email']),
            'mobile_phone' => $this->scape($this->values['movil']),
            'home_phone'   => $this->scape($this->values['telefono']),
        ]);

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
        
        $values = [
            $this->values['baja'] === null ? 1 : 0,
            $this->scape($this->values['apellido']),
            $this->scape($this->values['nombre']),
            $this->scape($this->values['documento']),
            $this->values['fecha_nacimiento'] ? '"'.$this->values['fecha_nacimiento']->format('Y-m-d H:i:s').'"' : 'null',
            $this->values['genero_masculino'] == true ? 'M' : 'F',
            $this->values['riesgo'],
            $this->scape($this->values['legajo_interno']),
            $this->scape($this->values['prontuario']),
            $this->scape($contact),
            $this->scape($required_documentation),
            $this->values['residency_id'],
            $this->now,
            $this->now
        ];

        $q_person = 'INSERT INTO people (enabled, last_name, name, document_type, document_number, birthday, sex, risk, register_number, pna, contact, required_documentation, residency_id, created_at, updated_at)
                     VALUES (:enabled, ":last_name", ":name", 0, ":document_number", :birthday, ":sex", :risk, ":register_number", ":pna", ":contact", ":required_documentation", :residency_id, ":created_at", ":updated_at")';

        $q_person = str_replace(
            [":enabled", ":last_name", ":name", ":document_number", ":birthday", ":sex", ":risk", ":register_number", ":pna", ":contact", ":required_documentation", ":residency_id", ":created_at", ":updated_at"],
            $values,
            $q_person
        );

        if(!mysqli_query($this->mysql, $q_person)) {
            echo $q_person . PHP_EOL;
            die('Error creating the person.'.PHP_EOL.mysqli_error($this->mysql));
        }

        $person_id = mysqli_insert_id($this->mysql);

        // Now, we have to save the person job.
        $empresa_tarea = strtoupper($this->values['empresa_tarea']);
        $empresa_tarea = $this->scape($empresa_tarea);
        $empresa_tarea = str_replace('-',               ' ',            $empresa_tarea);
        $empresa_tarea = str_replace('.',               '',             $empresa_tarea);
        $empresa_tarea = str_replace('/',               '',             $empresa_tarea);
        $empresa_tarea = str_replace('.',               '',             $empresa_tarea);
        $empresa_tarea = str_replace('PBIP',            'P.B.I.P.',     $empresa_tarea);
        $empresa_tarea = str_replace('ESTIBADO ',       'ESTIBADOR ',   $empresa_tarea);
        $empresa_tarea = str_replace('PROFECIONAL ',    'PROFESIONAL ', $empresa_tarea);
        $empresa_tarea = preg_replace('/^[^A-Za-z]+/',  '',             $empresa_tarea); // Numbers from the beginning
        $empresa_tarea = preg_replace('!\s+!',          ' ',            $empresa_tarea); // Multiple whitespace
        $empresa_tarea = $this->removeAccentMarks($empresa_tarea);
        if(empty($empresa_tarea)) {
            $empresa_tarea = 'XXXXX';
        }

        $q_activity = 'SELECT id FROM activities WHERE name = "' . $empresa_tarea . '"';
        if($result = mysqli_query($this->mysql, $q_activity)) {
            if(mysqli_num_rows($result) > 0) {
                $activity_id = mysqli_fetch_assoc($result)['id'];
            }
            else {
                $q_activity = 'INSERT INTO activities (name, created_at, updated_at) VALUES (":name", ":created_at", ":updated_at")';
                $q_activity = str_replace([":name", ":created_at", ":updated_at"], [$empresa_tarea, $this->now, $this->now], $q_activity);
        
                if(!mysqli_query($this->mysql, $q_activity)) {
                    echo $q_activity . PHP_EOL;
                    die('Error creating the activity.'.PHP_EOL.mysqli_error($this->mysql));
                }
        
                $activity_id = mysqli_insert_id($this->mysql);
            }
        }

        $q_job = 'INSERT INTO company_people (person_id, company_id, activity_id, art_company, art_number, subactivities, created_at, updated_at)
                  VALUES (:person_id, :company_id, :activity_id, "xxxxx", "000000", ":subactivities", ":created_at", ":updated_at")';

        $q_job = str_replace(
            [":person_id", ":company_id", ":activity_id", ":subactivities", ":created_at", ":updated_at"],
            [$person_id, $this->values['company_id'], $activity_id, $this->scape(json_encode([])), $this->now, $this->now],
            $q_job
        );

        if(!mysqli_query($this->mysql, $q_job)) {
            echo $q_job . PHP_EOL;
            die('Error creating the person job.'.PHP_EOL.mysqli_error($this->mysql));
        }

        $job_id = mysqli_insert_id($this->mysql);

        // Now, we have to save each card for the job.
        $q_cards = 'SELECT P.fc, P.numero, CASE WHEN P.id_grupo IS NOT NULL THEN 1 ELSE 0 END AS activa, U.validez_desde, U.validez_hasta
                    FROM dbo.Pin_por_usuario AS PU, dbo.Usuario AS U, dbo.Pin as P
                    where U.id_usuario = PU.id_usuario AND P.id_pin = PU.id_pin AND PU.id_usuario = ' . $this->values['id_usuario'];

        $cards = sqlsrv_query($this->mssql, $q_cards);
        while($card_row = sqlsrv_fetch_array($cards, SQLSRV_FETCH_ASSOC)) {
            $q_cards = 'INSERT INTO cards (fc, number, person_company_id, active, `from`, until, created_at, updated_at)
                        VALUES (:fc, ":number", :person_company_id, ":active", ":from", ":until", ":created_at", ":updated_at")';

            $values = [
                $this->scape($card_row['fc']),
                $this->scape($card_row['numero']),
                $job_id,
                $this->scape($card_row['activa']),
                $this->values['validez_desde'] ? $this->values['validez_desde']->format('Y-m-d H:i:s') : 'null',
                $this->values['validez_hasta'] ? $this->values['validez_hasta']->format('Y-m-d H:i:s') : 'null',
                $this->now,
                $this->now,
            ];

            $q_cards = str_replace(
                [":fc", ":number", ":person_company_id", ":active", ":from", ":until", ":created_at", ":updated_at"],
                $values,
                $q_cards
            );

            if(!mysqli_query($this->mysql, $q_cards)) {
                echo $q_cards . PHP_EOL;
                die('Error creating the card.'.PHP_EOL.mysqli_error($this->mysql));
            }
        }

        $q_vehicles = 'SELECT id_vehiculo_migracion FROM dbo.vehiculo_usuario WHERE id_vehiculo_migracion IS NOT NULL AND id_usuario = ' . $this->values['id_usuario'];
        $vehicles = sqlsrv_query($this->mssql, $q_vehicles);
        while($vehicle_row = sqlsrv_fetch_array($vehicles, SQLSRV_FETCH_ASSOC)) {
            $q_person_vehicle = 'INSERT INTO person_vehicle (person_id, vehicle_id, created_at, updated_at)
                                 VALUES (:person_id, :vehicle_id, ":created_at", ":updated_at")';
            
            $values = [
                $person_id,
                $vehicle_row['id_vehiculo_migracion'],
                $this->now,
                $this->now,
            ];

            $q_person_vehicle = str_replace(
                [":person_id", ":vehicle_id", ":created_at", ":updated_at"],
                $values,
                $q_person_vehicle
            );

            if(!mysqli_query($this->mysql, $q_person_vehicle)) {
                echo $q_person_vehicle . PHP_EOL;
                die('Error creating the vehicle person relationship.'.PHP_EOL.mysqli_error($this->mysql));
            }
        }


        return $person_id;
    }
}
