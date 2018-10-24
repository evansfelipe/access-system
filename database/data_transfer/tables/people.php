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

        return mysqli_insert_id($this->mysql);
    }
}
