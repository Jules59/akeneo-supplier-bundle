<?php

namespace Cgi\SupplierBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class CgiSupplierExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('entities.yml');
        $loader->load('normalizers.yml');
        $loader->load('updaters.yml');
        $loader->load('factories.yml');
        $loader->load('repositories.yml');
        $loader->load('jobs.yml');
        $loader->load('steps.yml');
        $loader->load('processors.yml');
        $loader->load('writers.yml');
        $loader->load('savers.yml');
        $loader->load('job_constraints.yml');
        $loader->load('job_defaults.yml');
        $loader->load('providers.yml');
        $loader->load('subscribers.yml');
        $loader->load('managers.yml');
    }
}
