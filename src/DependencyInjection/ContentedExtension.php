<?php
namespace Contented\DependencyInjection;

use Contented\ContentedConfiguration;
use Contented\ContentLoader\ContentLoaderInterface;
use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use Contented\ContentPage\Renderer\ContentPageRendererInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ContentedExtension extends Extension
{
    public const PARAMETER_CONTENT_PATH = 'contented.content_path';
    public const PARAMETER_LANGUAGES = 'contented.languages';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new ContentedConfiguration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter(static::PARAMETER_CONTENT_PATH, $config['content_path']);
        $container->setParameter(static::PARAMETER_LANGUAGES, $config['languages']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $container->registerForAutoconfiguration(ContentModuleInterface::class)->addTag('contented.content_module');
        $container->registerForAutoconfiguration(ContentPageInterface::class)->addTag('contented.content_page');
        $container->registerForAutoconfiguration(ContentLoaderInterface::class)->addTag('contented.content_loader');
        $container->registerForAutoconfiguration(ContentPageRendererInterface::class)->addTag('contented.content_renderer');
    }
}