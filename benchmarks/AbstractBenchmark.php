<?php

declare(strict_types=1);

namespace YorCreative\DataValidation\Benchmarks;

abstract class AbstractBenchmark
{
    protected int $iterations;

    public function __construct(int $iterations = 3)
    {
        if ($iterations <= 0) {
            throw new \InvalidArgumentException('Iterations must be greater than zero.');
        }
        $this->iterations = $iterations;
        $this->setupValidator();
    }

    abstract public function getName(): string;

    abstract protected function setupValidator(): void;

    abstract protected function performValidation(array $data, array $currentRules): bool;

    protected function getRules(): array
    {
        return [
            'users.*.profile.email' => 'required|email',
            'users.*.profile.age' => 'required|integer|between:18,100',
            'users.*.settings.theme' => 'required|string|in:light,dark',
            'users.*.settings.api_url' => 'required|url',
            'users.*.settings.config' => 'required|json',
        ];
    }

    protected function getInvalidRules(): array
    {
        return [
            'users.*.profile.email' => 'required|invalid_rule',
            'users.*.profile.age' => 'required|min:abc',
        ];
    }

    protected function getInvalidDataRules(): array
    {
        return [
            'users.*.profile.email' => 'required|email',
            'users.*.profile.age' => 'required|integer|between:18,100',
            'users.*.settings.theme' => 'required|string|in:light,dark',
            'users.*.settings.api_url' => 'required|url',
            'users.*.settings.config' => 'required|json',
        ];
    }

    public static function generateData(int $count, int $nestingLevel = 3): \Generator
    {
        for ($i = 0; $i < $count; $i++) {
            $profile = [
                'email' => "user{$i}@example.com",
                'age' => rand(18, 100),
            ];
            $settings = [
                'theme' => rand(0, 1) ? 'light' : 'dark',
                'api_url' => "https://api.example.com/user/{$i}",
                'config' => json_encode(['timeout' => 30, 'retry' => 3]),
            ];
            yield ['profile' => $profile, 'settings' => $settings];
        }
    }

    public static function generateInvalidData(int $count, int $nestingLevel = 3): \Generator
    {
        for ($i = 0; $i < $count; $i++) {
            $profile = [
                'email' => str_repeat('invalid-email', 100),
                'age' => 'not-an-integer',
            ];
            $settings = [
                'theme' => 'invalid-theme',
                'api_url' => 'not-a-url',
                'config' => str_repeat('invalid-json', 100),
                'extra' => array_fill(0, 100, 'data'),
            ];
            yield ['profile' => $profile, 'settings' => $settings];
        }
    }

    public function runForSizes(array $sizes): array
    {
        $resultsForAllSizes = [];

        foreach ($sizes as $size) {
            $validTimes = [];
            $validMemories = [];
            $validPeakMemories = [];
            $invalidTimes = [];
            $invalidMemories = [];
            $invalidPeakMemories = [];
            $errorTimes = [];
            $errorMemories = [];
            $errorPeakMemories = [];

            for ($i = 0; $i < $this->iterations; $i++) {
                // Valid data
                $startMem = memory_get_usage(true);
                $validData = ['users' => []];
                foreach (self::generateData($size) as $item) {
                    $validData['users'][] = $item;
                }
                $startTime = hrtime(true);
                $this->performValidation($validData, $this->getRules());
                $endTime = hrtime(true);
                $endMem = memory_get_usage(true);
                $peakMem = memory_get_peak_usage(true);
                $validTimes[] = ($endTime - $startTime) / 1e9;
                $validMemories[] = ($endMem - $startMem) / (1024 * 1024);
                $validPeakMemories[] = ($peakMem - $startMem) / (1024 * 1024);
                unset($validData);
                gc_collect_cycles();

                // Invalid data
                $startMem = memory_get_usage(true);
                $invalidData = ['users' => []];
                foreach (self::generateInvalidData($size) as $item) {
                    $invalidData['users'][] = $item;
                }
                $startTime = hrtime(true);
                $this->performValidation($invalidData, $this->getInvalidDataRules());
                $endTime = hrtime(true);
                $endMem = memory_get_usage(true);
                $peakMem = memory_get_peak_usage(true);
                $invalidTimes[] = ($endTime - $startTime) / 1e9;
                $invalidMemories[] = ($endMem - $startMem) / (1024 * 1024);
                $invalidPeakMemories[] = ($peakMem - $startMem) / (1024 * 1024);
                unset($invalidData);
                gc_collect_cycles();

                // Malformed rules
                $startMem = memory_get_usage(true);
                $validData = ['users' => []];
                foreach (self::generateData($size) as $item) {
                    $validData['users'][] = $item;
                }
                try {
                    $startTime = hrtime(true);
                    $this->performValidation($validData, $this->getInvalidRules());
                    $endTime = hrtime(true);
                    $endMem = memory_get_usage(true);
                    $peakMem = memory_get_peak_usage(true);
                    $errorTimes[] = ($endTime - $startTime) / 1e9;
                    $errorMemories[] = ($endMem - $startMem) / (1024 * 1024);
                    $errorPeakMemories[] = ($peakMem - $startMem) / (1024 * 1024);
                } catch (\InvalidArgumentException) {
                    // Expected for YorCreative
                }
                unset($validData);
                gc_collect_cycles();
            }

            // Log raw memory differences for debugging
            error_log("Size $size, Validator {$this->getName()}:");
            error_log("Valid Memories: " . json_encode($validMemories));
            error_log("Valid Peak Memories: " . json_encode($validPeakMemories));
            error_log("Invalid Memories: " . json_encode($invalidMemories));
            error_log("Invalid Peak Memories: " . json_encode($invalidPeakMemories));
            error_log("Error Memories: " . json_encode($errorMemories));
            error_log("Error Peak Memories: " . json_encode($errorPeakMemories));

            $resultsForAllSizes[$size] = [
                'valid' => [
                    'time' => $validTimes ? array_sum($validTimes) / $this->iterations : 0,
                    'mem' => $validMemories ? array_sum($validMemories) / count($validMemories) : 0,
                    'peak_mem' => $validPeakMemories ? array_sum($validPeakMemories) / count($validPeakMemories) : 0,
                ],
                'invalid' => [
                    'time' => $invalidTimes ? array_sum($invalidTimes) / $this->iterations : 0,
                    'mem' => $invalidMemories ? array_sum($invalidMemories) / count($invalidMemories) : 0,
                    'peak_mem' => $invalidPeakMemories ? array_sum($invalidPeakMemories) / count($invalidPeakMemories) : 0,
                ],
                'error' => [
                    'time' => $errorTimes ? array_sum($errorTimes) / count($errorTimes) : null,
                    'mem' => $errorMemories ? max($errorMemories) : null,
                    'peak_mem' => $errorPeakMemories ? max($errorPeakMemories) : null,
                ],
            ];
            \YorCreative\DataValidation\RuleRegistry::clearRegistry();
            gc_collect_cycles();
        }

        return $resultsForAllSizes;
    }

    public static function printIndividualReport(array $benchmarkResults, string $validatorName, array $sizes): void
    {
        echo "Benchmark Report for: $validatorName\n";
        echo "| " . str_pad('Items', 7) .
            " | " . str_pad('Valid Time (s)', 17) .
            " | " . str_pad('Valid Mem (MB)', 17) .
            " | " . str_pad('Valid Peak Mem (MB)', 20) .
            " | " . str_pad('Invalid Time (s)', 17) .
            " | " . str_pad('Invalid Mem (MB)', 17) .
            " | " . str_pad('Invalid Peak Mem (MB)', 20) .
            " | " . str_pad('Error Time (s)', 17) .
            " | " . str_pad('Error Mem (MB)', 17) .
            " | " . str_pad('Error Peak Mem (MB)', 20) . " |\n";
        echo "| " . str_repeat('-', 7) .
            " | " . str_repeat('-', 17) .
            " | " . str_repeat('-', 17) .
            " | " . str_repeat('-', 20) .
            " | " . str_repeat('-', 17) .
            " | " . str_repeat('-', 17) .
            " | " . str_repeat('-', 20) .
            " | " . str_repeat('-', 17) .
            " | " . str_repeat('-', 17) .
            " | " . str_repeat('-', 20) . " |\n";

        foreach ($sizes as $size) {
            if (!isset($benchmarkResults[$size])) {
                continue;
            }
            $result = $benchmarkResults[$size];
            $validTime = number_format($result['valid']['time'], 3);
            $validMem = number_format($result['valid']['mem'], 7);
            $validPeakMem = isset($result['valid']['peak_mem']) ? number_format($result['valid']['peak_mem'], 7) : 'N/A';
            $invalidTime = number_format($result['invalid']['time'], 3);
            $invalidMem = number_format($result['invalid']['mem'], 7);
            $invalidPeakMem = isset($result['invalid']['peak_mem']) ? number_format($result['invalid']['peak_mem'], 7) : 'N/A';
            $errorTime = $result['error']['time'] ? number_format($result['error']['time'], 3) : 'N/A';
            $errorMem = $result['error']['mem'] ? number_format($result['error']['mem'], 7) : 'N/A';
            $errorPeakMem = isset($result['error']['peak_mem']) ? number_format($result['error']['peak_mem'], 7) : 'N/A';

            echo "| " . str_pad((string)$size, 7) .
                " | " . str_pad($validTime, 17, ' ', STR_PAD_LEFT) .
                " | " . str_pad($validMem, 17, ' ', STR_PAD_LEFT) .
                " | " . str_pad($validPeakMem, 20, ' ', STR_PAD_LEFT) .
                " | " . str_pad($invalidTime, 17, ' ', STR_PAD_LEFT) .
                " | " . str_pad($invalidMem, 17, ' ', STR_PAD_LEFT) .
                " | " . str_pad($invalidPeakMem, 20, ' ', STR_PAD_LEFT) .
                " | " . str_pad($errorTime, 17, ' ', STR_PAD_LEFT) .
                " | " . str_pad($errorMem, 17, ' ', STR_PAD_LEFT) .
                " | " . str_pad($errorPeakMem, 20, ' ', STR_PAD_LEFT) . " |\n";
        }
        echo "\n";
    }
}