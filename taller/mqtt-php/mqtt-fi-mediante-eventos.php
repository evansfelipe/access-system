<?php
$c = new Mosquitto\Client();
$c->onConnect(function($code, $message) {
    echo "I'm connected\n";
});

$c->connect('mqtt.fi.mdp.edu.ar', 1883, 60);
$c->subscribe('#', 1);
$c->onMessage(function($m) {
    echo json_encode( (array)$m );
    echo "\n";
});
$c->onDisconnect(function($e){
    if ($e == 0){
	echo "ME fui porque quiero";
    } else {
	echo "No se porque me fui ".$e;
    }
    echo "Me desconectaro.. espero un rato y vuelvo.. ;)";
    sleep (10);
    $c = $GLOBALS["c"];
    $c->setReconnectDelay(5);
    die();

});




$socket = $c->getSocket();
$base = new EventBase();
$ev = new Event($base, $socket, Event::READ | Event::PERSIST, 'cb', $base);
function cb($fd, $what, $arg) {
    global $c;
    echo "Triggered: argumentos del evento.. \t";
    
    echo (json_encode (func_get_args()));
    $c->loop();
}
$ev->add();
$base->dispatch();
