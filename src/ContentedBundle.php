<?php
namespace Contented;

use Contented\DependencyInjection\ContentedCompilerPass;
use Contented\DependencyInjection\ContentedExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ContentedCompilerPass());
    }
}