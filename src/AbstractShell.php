<?php

namespace Ddrv\Shell;

use Ddrv\Shell\Contract\DescriptorInterface;
use Ddrv\Shell\Contract\Result;
use Ddrv\Shell\Contract\ShellInterface;
use Ddrv\Shell\Descriptor\ReadablePipe;
use Ddrv\Shell\Descriptor\WritablePipe;

abstract class AbstractShell implements ShellInterface
{

    /**
     * @var string|null
     */
    protected $cwd;

    /**
     * @var bool
     */
    protected $mergeErrorsAndOutput;

    /**
     * @var array
     */
    protected $env = [];

    /**
     * @var array
     */
    protected $descriptors = [];

    public function __construct(?string $cwd = null, ?array $env = null, bool $mergeErrorsAndOutput = false)
    {
        if ($mergeErrorsAndOutput) {
            $this->mergeErrorsAndOutput();
        } else {
            $this->splitErrorsAndOutput();
        }
        $this->setCwd($cwd);
        if ($env) {
            foreach ($env as $name => $value) {
                $this->setEnv($name, $value);
            }
        }
        $this->setDescriptor(new ReadablePipe(), ShellInterface::DESCRIPTOR_INPUT);
        $this->setDescriptor(new WritablePipe(), ShellInterface::DESCRIPTOR_OUTPUT);
        $this->setDescriptor(new WritablePipe(), ShellInterface::DESCRIPTOR_ERRORS);
    }

    public function setDescriptor(DescriptorInterface $descriptor, int $key)
    {
        $this->descriptors[$key] = $descriptor->get();
    }

    public function setCwd(?string $cwd): ShellInterface
    {
        $this->cwd = $cwd;
        return $this;
    }

    public function setEnv(string $name, string $value): ShellInterface
    {
        $this->env[$name] = $value;
        return $this;
    }

    public function mergeErrorsAndOutput(): ShellInterface
    {
        $this->mergeErrorsAndOutput = true;
        return $this;
    }

    public function splitErrorsAndOutput(): ShellInterface
    {
        $this->mergeErrorsAndOutput = false;
        return $this;
    }

    protected function run(string $command, ?string $cwd = null, ?array $env = null, bool $isBackground = false): Result
    {
        $out = '';
        if ($this->mergeErrorsAndOutput) $out = ' 2>&1';
        if ($isBackground) {
            $out = ' > /dev/null 2>&1 &';
        }
        $command .= $out;
        $cwd = $cwd ? $cwd : $this->cwd;
        $env = $env ? array_replace($this->env, $env) : $this->env;
        $exec = proc_open($command, $this->descriptors, $pipes, $cwd, $env, null);
        $output = null;
        $errors = null;
        if (is_resource($pipes[1])) $output = stream_get_contents($pipes[1]);
        if (is_resource($pipes[2])) $errors = stream_get_contents($pipes[2]);
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }
        $exitCode = proc_close($exec);
        return new Result((int)$exitCode, $output, $errors);
    }
}