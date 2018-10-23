<?php

class Residency extends BaseClass
{
    public function __construct($mysql, $values) {
        parent::__construct($mysql, $values);
    }

    public function save() {
        $values = [
            $this->scape($this->values['domicilio']),
            strlen($this->values['codigo_postal']) < 10 ? $this->scape($this->values['codigo_postal']) : null,
            $this->scape($this->values['pais']),
            $this->scape($this->values['provincia_estado']),
            $this->scape($this->values['ciudad']),
            $this->now,
            $this->now
        ];

        $q_residency = 'INSERT INTO residencies (street, cp, country, province, city, created_at, updated_at)
                        VALUES (":street", ":cp", ":country", ":province", ":city", ":created_at", ":updated_at")';

        $q_residency = str_replace([':street', ':cp', ':country', ':province', ':city', ':created_at', ':updated_at'], $values, $q_residency);

        if(!mysqli_query($this->mysql, $q_residency)) {
            echo $q_residency . PHP_EOL;
            die('Error creating the company residency.'.PHP_EOL.mysqli_error($this->mysql));
        }

        return mysqli_insert_id($this->mysql);
    }
}
