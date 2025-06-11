<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '4G');

require_once __DIR__ . '/../vendor/autoload.php';

use YorCreative\DataValidation\Benchmarks\DataValidationBenchmark;
use YorCreative\DataValidation\Benchmarks\IlluminateValidationBenchmark;

$sizes = [1000, 2000, 3000, 5000];
$iterations = 3;

$benchmarkClasses = [
    DataValidationBenchmark::class,
    IlluminateValidationBenchmark::class,
];

$allResults = [];

foreach ($benchmarkClasses as $class) {
    $benchmark = new $class($iterations);
    echo "Running benchmarks for: " . $benchmark->getName() . "...\n";
    $allResults[$benchmark->getName()] = $benchmark->runForSizes($sizes);
    gc_collect_cycles();
}

// Define scenarios and metrics based on output
$scenarios = ['valid', 'invalid'];
$metrics = ['time', 'mem', 'peak_mem'];
$displayNames = [
    'time' => 'Time(s)',
    'mem' => 'Mem(MB)',
    'peak_mem' => 'Peak Mem(MB)',
];

// Generate a table for each benchmark class
foreach ($benchmarkClasses as $class) {
    $benchmark = new $class($iterations);
    $name = $benchmark->getName();

    // Define table headers
    $headerParts = ['Items'];
    $columnWidths = ['Items' => 7];
    foreach ($scenarios as $scenario) {
        foreach ($metrics as $metric) {
            $header = ucfirst($scenario) . ' ' . $displayNames[$metric];
            $headerParts[] = $header;
            $columnWidths[$header] = (strpos($displayNames[$metric], 'Peak') !== false) ? 20 : 17;
        }
    }

    // Print table
    echo "\nBenchmark Results for $name:\n";
    echo '| ' . implode(' | ', array_map(function ($header) use ($columnWidths) {
            return str_pad($header, $columnWidths[$header]);
        }, $headerParts)) . " |\n";
    echo '| ' . implode(' | ', array_map(function ($header) use ($columnWidths) {
            return str_repeat('-', $columnWidths[$header]);
        }, $headerParts)) . " |\n";

    // Generate table rows
    foreach ($sizes as $size) {
        $rowParts = [(string)$size];
        $result = $allResults[$name][$size] ?? null;

        foreach ($scenarios as $scenario) {
            foreach ($metrics as $metric) {
                if ($result && isset($result[$scenario][$metric])) {
                    $value = $result[$scenario][$metric];
                    $precision = ($metric === 'time') ? 3 : 7;
                    $formatted = number_format($value, $precision);
                } else {
                    $formatted = 'N/A';
                }
                $rowParts[] = str_pad($formatted, $columnWidths[ucfirst($scenario) . ' ' . $displayNames[$metric]], ' ', STR_PAD_LEFT);
            }
        }
        echo '| ' . implode(' | ', $rowParts) . " |\n";
    }
}

unset($allResults);
gc_collect_cycles();

echo "- Metrics include valid data, invalid data, and malformed rules.\n";
echo "- Laravel's validation is slower for large arrays with wildcards. See https://github.com/laravel/ideas/issues/2212 and https://github.com/laravel/framework/issues/49375.\n";
