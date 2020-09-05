<?php

namespace perf\Vc;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class VcExtension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../config')
        );

        $loader->load('services.yml');
    }

    public function getNamespace()
    {
        return '';
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getAlias()
    {
        return 'perf.vc';
    }
}
