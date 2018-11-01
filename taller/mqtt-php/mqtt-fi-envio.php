<?php
// El loopForEver no me deja instalar la alarma tengo que usar otro comando.
//
//
pcntl_signal(SIGALRM, "alarm", false);

pcntl_alarm(5);

$client = new Mosquitto\Client();


echo "Iniciando Programa";

while (true) {
    pcntl_signal_dispatch();
    sleep(10);
}

function alarm(){
	echo "Alarma";
	$client = $GLOBALS["client"];
	$mysqli = new mysqli("localhost", "homestead", "secret", "homestead");
	if ($mysqli->connect_errno) {
		printf("Falló la conexión: %s\n", $mysqli->connect_error);
		//exit();
	} else {}
		try {
			echo date("H:i:s")." "; 
			echo "Conectando... ";
			$client->connect("mqtt.fi.mdp.edu.ar", 1883, 10);
			/* Consultas de selección que devuelven un conjunto de resultados */
			echo "Consulado a la base. ";
			$result_spool =  array();
			if ($resultado = $mysqli->query("SELECT * FROM messages_spool WHERE enviado=1 LIMIT 10")) {
				printf("La selección devolvió %d filas.\n", $resultado->num_rows);
				while ($row = mysqli_fetch_row($result)) {
					printf("%s\n", $row[0]);
					$result_spool[]=$row; 
				}
				/* liberar el conjunto de resultados */
				$resultado->close();
			}
			foreach ($result_spool as $row) {
				$topic = $row[2];
				$mensaje = $row[3];
				$client->publish( $topic, $mensaje);
				$resultado = $mysqli->query("UPDATE messages_spool SET enviado=1 where id=".$row[0]);
				$resultado->close();
			}
			echo "Saliendo...;) ";
			sleep (rand(3,20));
			echo date("H:i:s")."\n"; 
			pcntl_alarm(5);
			

	} catch (Exception $e) {
			echo $e->getMessage(), "\n";
	}

	



}

