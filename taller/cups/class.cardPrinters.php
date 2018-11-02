<?php

include 'vendor/autoload.php';


use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Model\Job;
use Smalot\Cups\Transport\Client;
use Smalot\Cups\Transport\ResponseParser;

class cardPrinters {
	public $client;
	public $builder;
	public $responseParser;
	public $user;
	public $pass;
	public $prefix = "Primacy";
	public $cardprinters;
	
	function cardPrinters($user="",$pass=""){
		// @TODO faltaria tomarlo de una configuración
		$this->user=$user;
		$this->pass=$pass;
		if (($user == "") && ($pass =="")){
			$this->client= new Client();
		} else {
			$this->client= new Client($user,$pass);		
		}
		$this->builder = new Builder();
		$this->responseParser = new ResponseParser();
		$this->cardprinters = array();
		
	}
	
	function listPrinter(){
		$printerManager = new PrinterManager($this->getBuilder(), $this->getClient(), $this->getResponseParser());
		$printers = $printerManager->getList();
		$cardprinters = $this->getCardPrinters();
		$cadena = $this->prefix;
		foreach ($printers as $printer) {
			if (preg_match("/$cadena/",$printer->getName())){
				$cardp=array();
				$cardp["nombre"]=$printer->getName();
				$cardp["status"]=$printer->getStatus();
				$cardp["uri"]=$printer->getUri();
				$cardprinters[] = $cardp;
				//var_dump($printer);
			}
		}
		
		$this->setCardPrinters($cardprinters);

		return json_encode($cardprinters);	
	}
	
	function listJobs($printer="default", $state=""){
		$uri = "-1";
		$cardprinters= $this->getCardPrinters();
		if ($printer=="default") {
			if ((isset($cardprinters[0])) && (isset($cardprinters[0]["uri"]))) {
				$uri = $cardPrinters[0]["uri"];	
			} 
		} else {
				//var_dump($cardprinters);
				foreach ($cardprinters as $card) {
					//var_dump($card);
					if (isset ($card["nombre"]) && ($card["nombre"] == $printer) ) {
						$uri = $card["uri"];
					}
				}
		}
		if ($uri == "-1") {
				return '{"error":"No hay impresoras disponibles"}';
			}
		if ($state == "") {
			$state = "not-completed";
			$limit = 0;
		} else {
			$state = "completed";
			$limit = 10;
		}
		
				
		$printerManager = new PrinterManager($this->getBuilder(), $this->getClient(), $this->getResponseParser());
		$printer = $printerManager->findByUri($uri);

		$jobManager = new JobManager($this->getBuilder(),$this->getClient(),$this->getResponseParser());
		$jobs = $jobManager->getList($printer, false, $limit, $state);
		
		$list = array();
		foreach ($jobs as $job) {
			$j = array();
			$j["id"]= $job->getId();
			$j["name"]= $job->getName();
			$j["state"] = $job->getState();
			$j["user"] = $job->getUsername();
			$j["copies"] = $job->getCopies();
			$j["page"] = $job->getPageRanges();
			$j["stateReason"] = $job->getStateReason();
			
			$list[] = $j;
			
		}
		
		return json_encode ($list);

	}
	
	function newJob($printer,$file, $titulo="",$systemUser="") {
		$uri = "-1";
		$cardprinters= $this->getCardPrinters();
		//var_dump($cardprinters);
		foreach ($cardprinters as $card) {
			//var_dump($card);
			if (isset ($card["nombre"]) && ($card["nombre"] == $printer) ) {
						$uri = $card["uri"];
			}
		}
		
		if ($uri == "-1") {
				return '{"error":"No hay impresoras disponibles", "OK":"0"}';
		}
		
		if (!file_exists($file)) {
				return '{"error":"No esta el archivo", "OK":"0"}';			
		}
	
		if ($systemUser=="") {
			$systemUser= $this->user;
		}
		if ($titulo==""){
			$titulo=$file;
		}
		$printerManager = new PrinterManager($this->getBuilder(), $this->getClient(), $this->getResponseParser());
		$printer = $printerManager->findByUri($uri);

		$jobManager = new JobManager($this->getBuilder(), $this->getClient(), $this->getResponseParser());

		$job = new Job();
		$job->setName($titulo);
		$job->setUsername($systemUser);
		$job->setCopies(1);
		$job->setPageRanges('1');
		$job->addFile($file);
		$job->addAttribute('document-format', 'Card');
		$job->addAttribute('media', 'Custom.54x85.6cm');
		//$job->addAttribute('fit-to-page', true);
		$result = $jobManager->send($printer, $job);
		return '{"error":"", "OK":"1"}';

	 }

	// setters and getters
	function setCardPrinters($cardPrinters){
		$this->cardprinters = $cardPrinters;
	}	
	

	function getCardPrinters(){
		return $this->cardprinters;
	}	
	
	function getBuilder() {
		return $this->builder;
	}
	
	function getClient() {
		return $this->client;
	}
	function getResponseParser(){
		return $this->responseParser;
	}

}


$CP = new cardPrinters("prueba","prueba");
echo "\n";
echo $CP->listPrinter(). "\n";;
echo "Lista en ejecución Primacy1\n";
echo $CP->listJobs("Primacy1"). "\n";
echo "Lista terminada de Primacy1 \n";
echo $CP->listJobs("Primacy1",1). "\n";
echo "Enviar a imprimir algo". "\n";
echo $CP->newJob("Primacy1","../imagen/tarjeta2.png","Nombre:Super,Prueba".rand(0,100)." -- DNI:1111111","logedUser"). "\n";
echo "Error en el nombre de impresora". "\n";
echo $CP->newJob("Primacy123","../imagen/tarjeta2.png","Nombre:Super,Prueba".rand(0,100)." -- DNI:1111111","logedUser"). "\n";
echo "Error en el nombre de archivo". "\n";
echo $CP->newJob("Primacy1","../imagen/tarjeta2123.png","Nombre:Super,Prueba".rand(0,100)." -- DNI:1111111\n","logedUser"). "\n";

/**
 * devolución del comando php class.cardPrinters.php

[{"nombre":"Primacy1","status":"idle","uri":"ipp:\/\/localhost:631\/printers\/Primacy1"}]
Lista en ejecución Primacy1
[]
Lista terminada de Primacy1 
[{"id":525,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":524,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":523,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":522,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":521,"name":"Koala 6","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":520,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":519,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":518,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":517,"name":"Job #","state":"canceled","user":"felipe","copies":1,"page":null,"stateReason":"job-canceled-by-user"},{"id":516,"name":"tarjeta2.png","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"}]
Enviar a imprimir algo{"error":"", "OK":"1"}Error en el nombre de impresora{"error":"No hay impresoras disponibles", "OK":"0"}Error en el nombre de archivo{"error":"No esta el archivo", "OK":"0"}felipe@felipe-notebook:~/Programacion/access-system/taller/cups$ 
felipe@felipe-notebook:~/Programacion/access-system/taller/cups$ 
felipe@felipe-notebook:~/Programacion/access-system/taller/cups$ php class.cardPrinters.php 

[{"nombre":"Primacy1","status":"processing","uri":"ipp:\/\/localhost:631\/printers\/Primacy1"}]
Lista en ejecución Primacy1
[{"id":526,"name":"Nombre:Super,Prueba7 -- DNI:1111111","state":"processing","user":"prueba","copies":1,"page":"1-1","stateReason":"job-printing"}]
Lista terminada de Primacy1 
[{"id":525,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":524,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":523,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":522,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":521,"name":"Koala 6","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":520,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":519,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":518,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":517,"name":"Job #","state":"canceled","user":"felipe","copies":1,"page":null,"stateReason":"job-canceled-by-user"},{"id":516,"name":"tarjeta2.png","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"}]
Enviar a imprimir algo
{"error":"", "OK":"1"}
Error en el nombre de impresora
{"error":"No hay impresoras disponibles", "OK":"0"}
Error en el nombre de archivo
{"error":"No esta el archivo", "OK":"0"}
felipe@felipe-notebook:~/Programacion/access-system/taller/cups$ php class.cardPrinters.php 

[{"nombre":"Primacy1","status":"processing","uri":"ipp:\/\/localhost:631\/printers\/Primacy1"}]
Lista en ejecución Primacy1
[{"id":526,"name":"Job #","state":"processing","user":null,"copies":1,"page":"1-1","stateReason":"job-printing"},{"id":527,"name":"Job #","state":"pending","user":null,"copies":1,"page":"1-1","stateReason":"none"}]
Lista terminada de Primacy1 
[{"id":525,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":524,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":523,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":522,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":521,"name":"Koala 6","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":520,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":519,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":518,"name":"Job #","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"},{"id":517,"name":"Job #","state":"canceled","user":"felipe","copies":1,"page":null,"stateReason":"job-canceled-by-user"},{"id":516,"name":"tarjeta2.png","state":"completed","user":"felipe","copies":1,"page":null,"stateReason":"job-completed-successfully"}]
*/
