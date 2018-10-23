<?php

class Company extends BaseClass
{
    public function __construct($mysql, $values) {
        parent::__construct($mysql, $values);
    }

    public function save() {
        $contact = json_encode([
            'web'   => $this->scape($this->values['web']),
            'fax'   => $this->scape($this->values['fax']),
            'phone' => $this->scape($this->values['telefono']),
            'email' => $this->scape($this->values['mail']),
        ]);

        $values = [
            $this->scape(strlen($this->values['razon_social']) > 0 ? $this->values['razon_social'] : $this->values['nombre_fantasia']),
            $this->scape(strlen($this->values['nombre_fantasia']) > 0 ? $this->values['nombre_fantasia'] : $this->values['razon_social']),
            $this->scape($this->values['actividad']),
            $this->scape($this->values['cuit']),
            $this->values['residency_id'],
            $this->now,
            $this->scape($contact),
            $this->now,
            $this->now
        ];

        $q_company = 'INSERT INTO companies (business_name, name, area, cuit, residency_id, expiration, contact, created_at, updated_at)
                      VALUES (":business_name", ":name", ":area", ":cuit", :residency_id, ":expiration", ":contact", ":created_at", ":updated_at")';

        $q_company = str_replace(
            [':business_name', ':name', ':area', ':cuit', ':residency_id', ':expiration', ':contact', ':created_at', ':updated_at'],
            $values,
            $q_company
        );

        if(!mysqli_query($this->mysql, $q_company)) {
            echo $q_company . PHP_EOL;
            die('Error creating the company.'.PHP_EOL.mysqli_error($this->mysql));
        }

        return mysqli_insert_id($this->mysql);
    }
}
