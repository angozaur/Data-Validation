<?php

namespace YorCreative\DataValidation\Benchmarks;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator as IlluminateTranslator;
use Illuminate\Validation\Factory as ValidatorFactory;

class IlluminateValidationBenchmark extends AbstractBenchmark
{
    private ValidatorFactory $validatorFactory;

    public function getName(): string
    {
        return 'Illuminate\\Validation';
    }

    protected function setupValidator(): void
    {
        $loader = new FileLoader(new Filesystem, __DIR__ . '/lang');
        if (!is_dir(__DIR__ . '/lang/en')) {
            @mkdir(__DIR__ . '/lang/en', 0777, true);
            if (!file_exists(__DIR__ . '/lang/en/validation.php')) {
                file_put_contents(__DIR__ . '/lang/en/validation.php', '<?php return [];');
            }
        }
        $translator = new IlluminateTranslator($loader, 'en');
        $this->validatorFactory = new ValidatorFactory($translator);
    }

    protected function performValidation(array $data, array $currentRules): bool
    {
        try {
            $validator = $this->validatorFactory->make($data, $currentRules);
            return !$validator->fails();
        } catch (\Exception) {
            return false; // Handle malformed rules gracefully
        }
    }
}