<?php
//Hay que loggear en algun lado

class CAlarma {
	public $device;
	public $mensaje;
	public $cmd;
	

	function CInicio($device){
		$this->device = $device;
	}
	
	function mensaje($mensaje){
		$this->mensaje = $mensaje;
		//echo "mensaje de ".$this->device." :".$mensaje->N." Comando: ".$mensaje->C." \n";
		$cmd = $this->getCommand();
		if ($cmd != "") {
			$cmd = $cmd." '".$this->device."' '".$mensaje->C."' '".json_encode($mensaje)."'";
			echo "Comando ejecutado: $cmd";
			$a = shell_exec("$cmd");
			return $a;
		} else {
			return NULL;
		}
	}
	
	function setCommand($cmd){
		$this->cmd =$cmd;
	}
	function getCommand(){
		return $this->cmd ;
	}

}

//{"N":"XXXX","A":"XXXX","C":"ALARMA","F":"XXXX"}
