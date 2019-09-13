<?php

namespace Ddrv\Shell\Descriptor;

use Ddrv\Shell\Contract\DescriptorInterface;

class File implements DescriptorInterface
{

    private $filename;

    private $mode;

    public function __construct(string $filename, string $mode = 'c+')
    {
        $this->filename = $filename;
        $this->mode = $mode;
    }

    public function get(): array
    {
        return ['file', $this->filename, $this->mode];
    }
}