<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '4G');

require_once __DIR__ . '/../vendor/autoload.php';

use YorCreative\DataValidation\Benchmarks\AbstractBenchmark;
use YorCreative\DataValidation\Benchmarks\IlluminateValidationBenchmark;

$sizes = [1000, 2000, 3000, 5000];
$iterations = 1;

echo "Running Illuminate Validation Benchmark Separately...\n";

$benchmark = new IlluminateValidationBenchmark($iterations);
$results = $benchmark->runForSizes($sizes);

AbstractBenchmark::printIndividualReport($results, $benchmark->getName(), $sizes);