<?php
//Hay que loggear en algun lado


class CInicio {
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
//{"N":"XXXX","A":"XXXX","C":"INICIO","F":"XXXX"}
