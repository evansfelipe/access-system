<?php

include 'vendor/autoload.php';

use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Model\Job;
use Smalot\Cups\Transport\Client;
use Smalot\Cups\Transport\ResponseParser;

$client = new Client("felipe","asfelipe");
$builder = new Builder();
$responseParser = new ResponseParser();

$printerManager = new PrinterManager($builder, $client, $responseParser);
$printer = $printerManager->findByUri('ipp://localhost:631/printers/Primacy1');

$jobManager = new JobManager($builder, $client, $responseParser);

$job = new Job();
$job->setName('Koala 7');
$job->setUsername('felipe');
$job->setCopies(1);
$job->setPageRanges('1');
$job->addFile('../imagen/tarjeta2.png');
$job->addAttribute('document-format', 'Card');
$job->addAttribute('media', 'Custom.54x85.6cm');
//$job->addAttribute('fit-to-page', true);
$result = $jobManager->send($printer, $job);
