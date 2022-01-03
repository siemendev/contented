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

    public function process(ContainerBuilder $container): void
    {
        $serviceContentLoaderDefinition = $container->getDefinition('contented.content_loaders.service');

        foreach (self::MAP as $serviceId => $method) {
            $taggedServices = $container->findTaggedServiceIds($serviceId);
            foreach ($taggedServices as $id => $service) {
                $serviceContentLoaderDefinition->addMethodCall($method, [new Reference($id)]);
            }
        }

        $this->addContentLoaders($container);
        $this->addRenderers($container);
    }

    private function addRenderers(ContainerBuilder $container): void
    {
        $rendererDefinition = $container->getDefinition('contented.content_renderer');
        foreach ($container->findTaggedServiceIds('contented.page_renderer') as $id => $service) {
            $rendererDefinition->addMethodCall('addContentPageRenderer', [new Reference($id)]);
        }
        foreach ($container->findTaggedServiceIds('contented.module_renderer') as $id => $service) {
            $rendererDefinition->addMethodCall('addContentModuleRenderer', [new Reference($id)]);
        }
    }

    private function addContentLoaders(ContainerBuilder $container): void
    {
        $contentLoaderDefinition = $container->getDefinition('contented.content_loader');
        foreach ($container->findTaggedServiceIds('contented.content_loader') as $id => $service) {
            $contentLoaderDefinition->addMethodCall('addContentLoader', [new Reference($id)]);
        }
    }
}