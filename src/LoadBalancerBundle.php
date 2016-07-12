<?php

namespace NBN\LoadBalancerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class LoadBalancerBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
