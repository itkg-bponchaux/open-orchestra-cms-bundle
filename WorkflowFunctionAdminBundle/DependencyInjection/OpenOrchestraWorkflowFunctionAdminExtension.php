<?php

namespace OpenOrchestra\WorkflowFunctionAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OpenOrchestraWorkflowFunctionAdminExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.yml');
        $loader->load('leftpanel.yml');
        $loader->load('transformer.yml');
        $loader->load('manager.yml');
        $loader->load('voter.yml');
        $loader->load('authorize_status_change.yml');
        $loader->load('subscriber.yml');
    }
}
