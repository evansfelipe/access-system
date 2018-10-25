<?php
include './tables/_base.php';
include './tables/residencies.php';
include './tables/companies.php';
include './tables/vehicles.php';
include './tables/people.php';

$mysql = mysqli_connect('192.168.0.104', 'homestead', 'secret', 'homestead');
$mssql = sqlsrv_connect('192.168.0.110\SQLEXPRESS, 49157', ["database" => "sa2010", "UID" => "sa", "pwd" => "Consorcio2018"]);

if(!($mssql && $mysql)) {
    echo "Connection could not be established.".PHP_EOL;
    if(!$mssql) {
        echo "Error with mssql.".PHP_EOL;
        print_r( sqlsrv_errors(), true);
    }
    if(!$mysql) {
        echo "Error with mysql.".PHP_EOL;
        print_r( mysqli_connect_error(), true);
    }
}
else {
    // Truncates the tables we are going to alter.
    $tables = ['residencies', 'companies', 'vehicles', 'vehicle_types', 'people', 'activities', 'company_people'];
    $q_truncate = "TRUNCATE TABLE :table";
    foreach ($tables as $table) {
        if(!mysqli_query($mysql, str_replace(':table', $table, $q_truncate))) {
            die('Error truncating the tables.'.PHP_EOL.mysqli_error($mysql));
        }
    }

    // Companies
    $q_companies = "SELECT * FROM dbo.Empresa WHERE id_empresa IN (SELECT MIN(id_empresa) AS id FROM dbo.Empresa WHERE cuit IS NOT NULL AND cuit <> '' AND LEN(cuit) <= 15 GROUP BY cuit)";
    $companies = sqlsrv_query($mssql, $q_companies);
    if(!$companies) {
       die(print_r( sqlsrv_errors(), true));
    }
    while($row = sqlsrv_fetch_array($companies, SQLSRV_FETCH_ASSOC)) {
        // Saves the residency for this company.
        $residency = new Residency($mysql, $row);
        $residency_id = $residency->save();
        // Saves the company.
        $company = new Company($mysql, array_merge($row, ['residency_id' => $residency_id]));
        $company_id = $company->save();
        // Saves the vehicles of this company.
        $q_vehicles = "SELECT * FROM dbo.Vehiculo AS V, (SELECT id_vehiculo, min(id_empresa) as id_empresa FROM dbo.vehiculo_empresa GROUP BY id_vehiculo) AS VE WHERE V.id_vehiculo = VE.id_vehiculo AND VE.id_empresa = ".$row['id_empresa'];
        $vehicles = sqlsrv_query($mssql, $q_vehicles);
        while($v_row = sqlsrv_fetch_array($vehicles, SQLSRV_FETCH_ASSOC)) {
            $vehicle = new Vehicle($mysql, array_merge($v_row, ['company_id' => $company_id]));
            $vehicle->save();
        }
        // Saves the people of this company.
        $p_query = "SELECT * FROM dbo.Usuario WHERE nombre IS NOT NULL AND nombre <> '' AND apellido IS NOT NULL AND apellido <> '' AND id_empresa = ".$row['id_empresa'];
        $people = sqlsrv_query($mssql, $p_query);
        while($p_row = sqlsrv_fetch_array($people, SQLSRV_FETCH_ASSOC)) {
            // var_dump($row);
            // exit();
            $p_row['domicilio'] = $p_row['direccion'];
            $p_row['provincia_estado'] = $p_row['provincia'];
            $residency = new Residency($mysql, $p_row);
            $residency_id = $residency->save();
            $person = new Person($mysql, array_merge($p_row, ['company_id' => $company_id, 'residency_id' => $residency_id]));
            $person->save();
        }
    }

    sqlsrv_free_stmt($companies);
}
?>