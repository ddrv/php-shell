<?php

namespace Ddrv\Shell\Contract;

class Result
{

    /**
     * @var int
     */
    private $exitCode;

    /**
     * @var string|null
     */
    private $output;

    /**
     * @var string|null
     */
    private $errors;

    public function __construct(int $exitCode, ?string $output, ?string $errors)
    {
        $this->exitCode = $exitCode;
        $this->output = $output;
        $this->errors = $errors;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function getErrors(): ?string
    {
        return $this->errors;
    }
}