<?php

/**
 * Programa para listar las impresoras.
 * devuelve un array de impresoras en JSON. 
 * Devuelve Nombre, Status y URI.
 * Que coincidan con la variable $cadena.
 * 
 * 
 */

include 'vendor/autoload.php';

use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Transport\Client;
use Smalot\Cups\Transport\ResponseParser;

$cadena="Primacy";

$client = new Client();
$builder = new Builder();
$responseParser = new ResponseParser();

$printerManager = new PrinterManager($builder, $client, $responseParser);
$printers = $printerManager->getList();

$cardprinters = array();
$cardp=array();
foreach ($printers as $printer) {
	if (preg_match("/$cadena/",$printer->getName())){
		$cardp["nombre"]=$printer->getName();
		$cardp["status"]=$printer->getStatus();
		$cardp["uri"]=$printer->getUri();
		$cardprinters[] = $cardp;
		//var_dump($printer);
	}
}

echo json_encode($cardprinters);
