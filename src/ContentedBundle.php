<?php
namespace siemendev\contended;

use siemendev\contended\DependencyInjection\ContentedExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContentedBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new ContentedExtension();
        }

        return $this->extension;
    }
}