<?php

class Person extends BaseClass
{
    public function __construct($mysql, $values) {
        parent::__construct($mysql, $values);
    }

    public function save() {
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

        $empresa_tarea = strtoupper($this->values['empresa_tarea']);
        $empresa_tarea = $this->scape($empresa_tarea);
        $empresa_tarea = str_replace('-', ' ', $empresa_tarea);
        $empresa_tarea = str_replace('.', '', $empresa_tarea);
        $empresa_tarea = str_replace('/', '', $empresa_tarea);
        $empresa_tarea = str_replace('.', '', $empresa_tarea);
        $empresa_tarea = str_replace('PBIP', 'P.B.I.P.', $empresa_tarea);
        $empresa_tarea = str_replace('ESTIBADO ', 'ESTIBADOR ', $empresa_tarea);
        $empresa_tarea = str_replace('PROFECIONAL ', 'PROFESIONAL ', $empresa_tarea);
        $empresa_tarea = preg_replace('/^[^A-Za-z]+/', '', $empresa_tarea); // Numbers from the beginning
        $empresa_tarea = preg_replace('!\s+!', ' ', $empresa_tarea); // Multiple whitespace
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

        return $person_id;
    }
}
