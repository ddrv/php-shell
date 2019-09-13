<?php

namespace Ddrv\Shell\Descriptor;

use Ddrv\Shell\Contract\DescriptorInterface;

class ReadablePipe implements DescriptorInterface
{

    public function get(): array
    {
        return ['pipe', 'r'];
    }
}