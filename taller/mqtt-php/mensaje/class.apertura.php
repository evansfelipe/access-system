<?php
//Hay que loggear en algun lado

class CApertura {
	public $device;
	public $mensaje;

	function CInicio($device){
		$this->device = $device;
	}
	
	function mensaje($mensaje){
		$this->mensaje = $mensaje;
		echo "mensaje de ".$this->device." :".$mensaje->N." Comando: ".$mensaje->C." \n";
		return NULL;
	}
	

}

//{"N":"XXXX","A":"XXXX","C":"APERTURA","ID":"XXXX","Ac":"XXX","F":"XXXX"}
