<?php
namespace Contented\DependencyInjection;

use Contented\ContendedConfiguration;
use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
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
        $configuration = new ContendedConfiguration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter(static::PARAMETER_CONTENT_PATH, $config['content_path']);
        $container->setParameter(static::PARAMETER_LANGUAGES, $config['languages']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $container->registerForAutoconfiguration(ContentModuleInterface::class)->addTag('contented.content_module');
        $container->registerForAutoconfiguration(ContentPageInterface::class)->addTag('contented.content_page');
    }
}