<?php

class Vehicle extends BaseClass
{
    public function __construct($mysql, $values) {
        parent::__construct($mysql, $values);
    }

    public function save() {

        $q_type = 'SELECT id FROM vehicle_types WHERE type = "' . trim($this->values['tipo_personalizado']) . '"';
        if($result = mysqli_query($this->mysql, $q_type)) {
            if(mysqli_num_rows($result) > 0) {
                $type_id = mysqli_fetch_assoc($result)['id'];
            }
            else {
                $q_type = 'INSERT INTO vehicle_types (type, created_at, updated_at) VALUES (":type", ":created_at", ":updated_at")';
                $q_type = str_replace([":type", ":created_at", ":updated_at"], [trim($this->values["tipo_personalizado"]), $this->now, $this->now], $q_type);
        
                if(!mysqli_query($this->mysql, $q_type)) {
                    echo $q_type . PHP_EOL;
                    die('Error creating the vehicle type.'.PHP_EOL.mysqli_error($this->mysql));
                }
        
                $type_id = mysqli_insert_id($this->mysql);
            }
        }

        $values = [
            $this->values['company_id'],
            $type_id,
            $this->scape($this->values['titular']),
            $this->scape($this->values['patente']),
            $this->scape($this->values['marca']),
            $this->scape($this->values['modelo']),
            $this->scape($this->values['color']),
            date('Y', $this->values['anio']),
            $this->now,//$this->scape($this->values['habilitacion']),
            'null',
            $this->now,
            $this->now
        ];

        $q_vehicle = 'INSERT INTO vehicles (company_id, type_id, owner, plate, brand, model, colour, year, insurance, vtv, created_at, updated_at)
                      VALUES (:company_id, :type_id, ":owner", ":plate", ":brand", ":model", ":colour", ":year", ":insurance", :vtv, ":created_at", ":updated_at")';

        $q_vehicle = str_replace(
            [":company_id", ":type_id", ":owner", ":plate", ":brand", ":model", ":colour", ":year", ":insurance", ":vtv", ":created_at", ":updated_at"],
            $values,
            $q_vehicle
        );

        if(!mysqli_query($this->mysql, $q_vehicle)) {
            echo $q_vehicle . PHP_EOL;
            die('Error creating the vehicle.'.PHP_EOL.mysqli_error($this->mysql));
        }

        return mysqli_insert_id($this->mysql);
    }
}
