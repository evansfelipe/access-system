<?php
echo "Iniciando Programa";
$client = new Mosquitto\Client();
$client->onConnect('connect');
$client->onDisconnect('disconnect');
$client->onSubscribe('subscribe');
$client->onMessage('message');
echo "Seteando testamento...";
$client->setWill('/client', "Client died :-(", 1, 0);
try {
	echo "Conectando...";
	$client->connect("mqtt.fi.mdp.edu.ar", 1883, 10);
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}


$client->subscribe('/cp/#', 1);
$client->setReconnectDelay(5);

$client->loopForever();
function connect($r) {
	echo "I got code {$r}\n";
}
function subscribe() {
	echo "Subscribed to a topic\n";
}
function message($message) {
	printf("Got a message on topic %s with payload:\n%s\n", $message->topic, $message->payload);
	$supertopic = $message->tokeniseTopic($message->topic);
	if (is_array($supertopic) && isset($supertopic[1])) {
		  try {
			$val = json_decode($message->payload);
			if (is_null($val)) {
				//throw ('Error');
					switch(json_last_error()) {
						case JSON_ERROR_NONE:
							echo ' - Sin errores';
						break;
						case JSON_ERROR_DEPTH:
							echo ' - Excedido tamaño máximo de la pila';
						break;
						case JSON_ERROR_STATE_MISMATCH:
							echo ' - Desbordamiento de buffer o los modos no coinciden';
						break;
						case JSON_ERROR_CTRL_CHAR:
							echo ' - Encontrado carácter de control no esperado';
						break;
						case JSON_ERROR_SYNTAX:
							echo ' - Error de sintaxis, JSON mal formado';
						break;
						case JSON_ERROR_UTF8:
							echo ' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta';
						break;
						default:
							echo ' - Error desconocido';
						break;
					}
				echo "Es vacio ".json_last_error();
			}
		  } catch (Exception $e) {
				echo '{"result":"FALSE","message":"Caught exception: ' .  $e->getMessage() . ' ~"}';
			}
		  //var_dump($val);
		  $Class = "C".ucfirst(strtolower($val->C));
		  $file = "class.".strtolower($val->C).".php";
		  if (file_exists("mensaje/$file")){
			  include_once("mensaje/$file");
			  $m=new $Class($supertopic[1]);
			  $return = $m->mensaje($val);
		  } else {
			  echo "no existe el mensaje";
		  }
		  if ($return != NULL) {
			  $client->publish( $message->topic, $return);
		  } else {
			  echo "No hay mensaje de vuelta en este mensaje\n";
		  }
		  
	}
}
	
	

function disconnect() {
	echo "Disconnected cleanly\n";
	$client = $GLOBALS["client"];
	$client->setReconnectDelay(5);
}


