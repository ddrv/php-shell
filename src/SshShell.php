<?php

namespace Ddrv\Shell;

use Ddrv\Shell\Contract\Result;

class SshShell extends AbstractShell
{

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string|null
     */
    private $identityFile;

    public function __construct(
        string $user,
        string $host,
        int $port = 22,
        ?string $identityFile = null,
        ?string $cwd = null,
        ?array $env = null,
        bool $mergeErrorsAndOutput = false
    )
    {
        $this->connect($user, $host, $port, $identityFile);
        parent::__construct($cwd, $env, $mergeErrorsAndOutput);
    }

    public function connect(string $user, string $host, int $port = 22, ?string $identityFile = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->identityFile = $identityFile;
    }

    public function exec(string $command, ?string $cwd = null, ?array $env = null): Result
    {
        $cwd = $cwd ? $cwd : $this->cwd;
        $env = $env ? array_replace($this->env, $env) : $this->env;
        $e = '';
        if ($env) {
            foreach ($env as $key => $value) {
                $e .= ' ' . $key . '="' . addslashes($value) . '"';
            }
        }
        $connect = '-p ' . $this->port;
        if ($this->identityFile) $connect .= ' -i ' . $this->identityFile;
        $connect .= ' ' . $this->user . '@' . $this->host;
        $cmd = 'rsh ' . $connect . ' "';
        if ($cwd) $cmd .= 'cd ' . $cwd . ' && ';
        $cmd .= $e . ' ' . $command . '"';
        return $this->run($cmd);
    }

    public function upload(string $sourceFile, string $destinationDirectory): bool
    {
        $cmd = 'scp -i ' . $this->identityFile . ' -p ' . $this->port . ' ' . $sourceFile . ' '
            . $this->user . '@' . $this->host . ':' . $destinationDirectory;
        $result = $this->run($cmd);
        return $result->getExitCode() ? false : true;
    }
}