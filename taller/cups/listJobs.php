<?php

include 'vendor/autoload.php';

use Http\Client\HttpClient;
use Smalot\Cups\Model\JobInterface;
use Smalot\Cups\CupsException;
use Smalot\Cups\Model\PrinterInterface;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Transport\Client;
use Smalot\Cups\Transport\ResponseParser;
use Smalot\Cups\Model\Job;
use Smalot\Cups\Transport\Response as CupsResponse;
use GuzzleHttp\Psr7\Request;

$client = new Client();
$builder = new Builder();
$responseParser = new ResponseParser();

$json = '[{"nombre":"Primacy1","status":"idle","uri":"ipp:\/\/localhost:631\/printers\/Primacy1"}]';





$printerManager = new PrinterManager($builder, $client, $responseParser);
$printer = $printerManager->findByUri('ipp://localhost:631/printers/Primacy1');

$jobManager = new JobManager($builder, $client, $responseParser);
$jobs = $jobManager->getList($printer, false, 0, 'completed');

foreach ($jobs as $job) {
    echo '#'.$job->getId().' '.$job->getName().' - '.$job->getState().PHP_EOL;
}
