<?php

namespace Contented\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ContentedCompilerPass implements CompilerPassInterface
{
    private const MAP = [
        'contented.content_page' => 'addContentPage',
        'contented.content_module' => 'addContentModule',
    ];

    public function process(ContainerBuilder $container)
    {
        $controllerDefinition = $container->getDefinition('contented.controller');

        foreach (self::MAP as $serviceId => $method) {
            $taggedServices = $container->findTaggedServiceIds($serviceId);
            foreach ($taggedServices as $class => $service) {
                $controllerDefinition->addMethodCall($method, [new Reference($class)]);
            }
        }
    }
}