<?php
namespace siemendev\contended\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Routing\Loader\XmlFileLoader;

class ContentedExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}