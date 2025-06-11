<?php

namespace YorCreative\DataValidation\Benchmarks;

use YorCreative\DataValidation\DataValidationConfig;
use YorCreative\DataValidation\Validator;

class DataValidationBenchmark extends AbstractBenchmark
{
    public function getName(): string
    {
        return 'YorCreative\\DataValidation';
    }

    protected function setupValidator(): void
    {
        // No specific factory setup needed, Validator is instantiated directly
    }

    protected function performValidation(array $data, array $currentRules): bool
    {
        $config = new DataValidationConfig();
        $config->optimizeForLargeDataset(count($data));
        $validator = Validator::make($data, $currentRules, [], [], true, $config);
        try {
            $result = $validator->validate();
            $validator->clearParsedRulesCache(); // Clear cache after each run
            return $result;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }
}