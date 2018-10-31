<?php

class Vehicle extends BaseClass
{
    public function __construct($mysql, $mssql, $values) {
        parent::__construct($mysql, $values);
        $this->mssql = $mssql;
    }

    public function save() {

        $tipo_personalizado = strtoupper($this->values['tipo_personalizado']);
        $tipo_personalizado = $this->scape($tipo_personalizado);
        $tipo_personalizado = str_replace('-', ' ', $tipo_personalizado);
        $tipo_personalizado = str_replace(',', '', $tipo_personalizado);
        $tipo_personalizado = str_replace('.', '', $tipo_personalizado);
        $tipo_personalizado = str_replace('/', '', $tipo_personalizado);
        $tipo_personalizado = str_replace(' X ', 'X', $tipo_personalizado);
        $tipo_personalizado = str_replace('*', 'X', $tipo_personalizado);
        $tipo_personalizado = str_replace('C/', 'CON ', $tipo_personalizado);
        $tipo_personalizado = str_replace('P/', 'PARA ', $tipo_personalizado);
        $tipo_personalizado = str_replace('D/', 'DOBLE ', $tipo_personalizado);
        $tipo_personalizado = str_replace('PTAS', 'PUERTAS', $tipo_personalizado);
        // Known misspells.
        $tipo_personalizado = str_replace(
            ['AUTI', 'AUTP', 'AUTOMOVIL'],
            'AUTO',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            ['CAMIION', 'CAMIKON', 'CAMON'],
            'CAMION',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            ['TRATOR', 'TRATOR', 'TRACTIOR'],
            'TRACTOR',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'FUEGON',
            'FURGON',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            ['GINCHE', 'GUINCHES'],
            'GUINCHE',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'PALAMECANICA',
            'PALA MECANICA',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'AUTO ELEVADOR',
            'AUTOELEVADOR',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            ['PLCK', 'PIICK', 'PIK', 'LICK'],
            'PICK',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'TARRENO',
            'TERRENO',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'LIGEROS',
            'LIGERO',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'VEHICULOS',
            'VEHICULO',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'CCHASIS',
            'CHASIS',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            ['IP', 'UO', 'YUP'],
            'UP',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            'PICKUP',
            'PICK UP',
            $tipo_personalizado
        );
        $tipo_personalizado = str_replace(
            ['SEMI9RREMOLQUE', 'SEMIREMOLQUE', 'SEMI REMOLQUE', 'SEMI REMOL', 'SEMIRROMOLQUE', 'SEMERREMOLQUE'],
            'SEMIRREMOLQUE',
            $tipo_personalizado
        );

        $tipo_personalizado = str_replace(
            'CCABINA',
            'C CABINA',
            $tipo_personalizado
        );

        $tipo_personalizado = str_replace(
            'C CABINA',
            'CON CABINA',
            $tipo_personalizado
        );

        $tipo_personalizado = str_replace(
            'DCABINA',
            'DOBLE CABINA',
            $tipo_personalizado
        );

        // Multiple whitespace.
        $tipo_personalizado = preg_replace('!\s+!', ' ', $tipo_personalizado);
        $tipo_personalizado = $this->removeAccentMarks($tipo_personalizado);

        if($tipo_personalizado === 'RURAL 5') {
            $tipo_personalizado = 'RURAL 5 PUERTAS';
        }

        if($tipo_personalizado === 'RURAL 5 P') {
            $tipo_personalizado = 'RURAL 5 PUERTAS';
        }

        if($tipo_personalizado === 'AMION') {
            $tipo_personalizado = 'CAMION';
        }

        if($tipo_personalizado === 'CAMION TRACTO') {
            $tipo_personalizado = 'CAMION TRACTOR';
        }

        if($tipo_personalizado === 'SEMIRREMOLQU') {
            $tipo_personalizado = 'SEMIRREMOLQUE';
        }

        if($tipo_personalizado === 'TRACTOR CARRETERA') {
            $tipo_personalizado = 'TRACTOR DE CARRETERA';
        }

        if(empty($tipo_personalizado)) {
            $tipo_personalizado = 'XXXXX';
        }

        $q_type = 'SELECT id FROM vehicle_types WHERE type = "' . $tipo_personalizado . '"';
        if($result = mysqli_query($this->mysql, $q_type)) {
            if(mysqli_num_rows($result) > 0) {
                $type_id = mysqli_fetch_assoc($result)['id'];
            }
            else {
                $q_type = 'INSERT INTO vehicle_types (type, created_at, updated_at) VALUES (":type", ":created_at", ":updated_at")';
                $q_type = str_replace([":type", ":created_at", ":updated_at"], [$tipo_personalizado, $this->now, $this->now], $q_type);
        
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

        $new_vehicle_id = mysqli_insert_id($this->mysql);

        $q_vehicle = 'UPDATE dbo.vehiculo_usuario SET id_vehiculo_migracion = ' . $new_vehicle_id . ' WHERE id_vehiculo = ' . $this->values['id_vehiculo'];
        $vehicles = sqlsrv_query($this->mssql, $q_vehicle);
        if(!$vehicles) {
           die(print_r( sqlsrv_errors(), true));
        }

        return $new_vehicle_id;
    }
}
