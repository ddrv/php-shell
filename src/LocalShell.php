<?php

namespace Ddrv\Shell;

use Ddrv\Shell\Contract\Result;

class LocalShell extends AbstractShell
{

    public function __construct(?string $cwd = null, ?array $env = null, bool $mergeErrorsAndOutput = false)
    {
        parent::__construct($cwd, $env, $mergeErrorsAndOutput);
    }

    public function exec(string $command, ?string $cwd = null, ?array $env = null): Result
    {
        return $this->run($command, $cwd, $env);
    }

    public function upload(string $sourceFile, string $destinationDirectory): bool
    {
        $filename = pathinfo($sourceFile, PATHINFO_BASENAME);
        return copy($sourceFile, $destinationDirectory . DIRECTORY_SEPARATOR . $filename);
    }
}