<?php

namespace Ddrv\Shell\Contract;

interface ShellInterface
{

    public function setCwd(?string $cwd): self;

    public function setEnv(string $name, string $value): self;

    public function mergeErrorsAndOutput(): self;

    public function splitErrorsAndOutput(): self;

    public function exec(string $command, ?string $cwd = null, ?array $env = null): Result;

    public function upload(string $sourceFile, string $destinationDirectory): bool;
}