<?php

namespace Ddrv\Shell;

use Ddrv\Shell\Contract\Result;
use Ddrv\Shell\Contract\ShellInterface;

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

    protected function run(string $command, ?string $cwd = null, ?array $env = null): Result
    {
        if ($this->mergeErrorsAndOutput) $command .= ' 2>&1';
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $cwd = $cwd ? $cwd : $this->cwd;
        $env = $env ? array_replace($this->env, $env) : $this->env;
        $exec = proc_open($command, $descriptors, $pipes, $cwd, $env, null);
        $output = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($exec);
        return new Result((int)$exitCode, $output, $errors);
    }
}