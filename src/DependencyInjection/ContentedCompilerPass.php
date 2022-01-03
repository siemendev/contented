<?php

namespace Contented\DependencyInjection;

use Contented\ContentLoader\TwigContentLoader;
use Contented\ContentModule\Renderer\TwigContentModuleRenderer;
use Contented\ContentPage\Renderer\TwigContentPageRenderer;
use Contented\Settings\SettingsTwigExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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

        if (class_exists('Twig\Environment')) {
            $this->addOptionalTwigServices($container);
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

    private function addOptionalTwigServices(ContainerBuilder $container): void
    {
        $container->setDefinition(
            'contented.settings_extension',
            (new Definition(SettingsTwigExtension::class, []))
                ->addArgument(new Reference('contented.settings_manager'))
                ->addTag('twig.extension'),
        );
        $container->setDefinition(
            'contented.renderer.page.twig',
            (new Definition(TwigContentPageRenderer::class, []))->addArgument(new Reference('twig'))->addTag('contented.page_renderer')
        );
        $container->setDefinition(
            'contented.renderer.module.twig',
            (new Definition(TwigContentModuleRenderer::class, []))->addArgument(new Reference('twig'))->addTag('contented.module_renderer')
        );
        $container->setDefinition(
            'contented.content_loaders.twig',
            (new Definition(TwigContentLoader::class, []))
                ->addArgument(new Reference('contented.renderer.page.twig'))
                ->addArgument(new Reference('contented.renderer.module.twig'))
                ->addTag('contented.content_loader', ['priority' => -99])
        );
    }
}