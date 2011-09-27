<?php

namespace TSEP\Bundle\SearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use Symfony\Component\Config\FileLocator;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;


class TSEPSearchExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        //TODO: Move to yaml.
    	$xmlloader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $xmlloader->load('services.xml');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

    }
}