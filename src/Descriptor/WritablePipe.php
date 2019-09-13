<?php

namespace Ddrv\Shell\Descriptor;

use Ddrv\Shell\Contract\DescriptorInterface;

class WritablePipe implements DescriptorInterface
{

    public function get(): array
    {
        return ['pipe', 'w'];
    }
}