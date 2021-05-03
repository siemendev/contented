<?php
namespace Contented\DependencyInjection;

use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ContentedExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $container->registerForAutoconfiguration(ContentModuleInterface::class)->addTag('contented.content_module');
        $container->registerForAutoconfiguration(ContentPageInterface::class)->addTag('contented.content_page');
    }
}