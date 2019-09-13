<?php

namespace Ddrv\Shell\Contract;

interface ShellInterface
{

    const DESCRIPTOR_INPUT  = 0;
    const DESCRIPTOR_OUTPUT = 1;
    const DESCRIPTOR_ERRORS = 2;

    public function setDescriptor(DescriptorInterface $descriptor, int $key);

    public function setCwd(?string $cwd): self;

    public function setEnv(string $name, string $value): self;

    public function mergeErrorsAndOutput(): self;

    public function splitErrorsAndOutput(): self;

    public function exec(string $command, ?string $cwd = null, ?array $env = null): Result;

    public function silent(string $command, ?string $cwd = null, ?array $env = null): void;

    public function upload(string $sourceFile, string $destinationDirectory): bool;
}