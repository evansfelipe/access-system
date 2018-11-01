<?php
//Hay que loggear en algun lado

class CPong {
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


// {"N":"XXXX","C","PONG","F": "XXXX","FA": "XXXX","UID": "XXXX","UFID":"XXXX","U_A":"XXXX","S":"XXXX" }
