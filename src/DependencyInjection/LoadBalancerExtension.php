<?php

namespace NBN\LoadBalancerBundle\DependencyInjection;

use NBN\LoadBalancer\LoadBalancer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadBalancerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('nbn_loadbalancer.load_limit', $config['load_limit']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('chooser.xml');

        //Instanciate LoadBalancer with corresponding chooser and hosts
        $container
            ->register('nbn_loadbalancer.loadbalancer', LoadBalancer::class)
            ->addArgument([])
            ->addArgument(new Reference(sprintf('nbn_loadbalancer.chooser.%s', $config['host_chooser'])));

        if (count($config['hosts'])) {
            $loadbalancer = $container->getDefinition('nbn_loadbalancer.loadbalancer');
            foreach ($config['hosts'] as $id => $hostConfiguration) {
                $loadbalancer->addMethodCall('addHostByConfiguration', [$id, $hostConfiguration['url'], $hostConfiguration['settings']]);
            }
        }
    }
}
