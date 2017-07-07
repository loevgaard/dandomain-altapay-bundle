<?php
namespace Loevgaard\DandomainAltapayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('loevgaard_dandomain_altapay');

        $rootNode
            ->children()
                ->scalarNode('altapay_username')->end()
                ->scalarNode('altapay_password')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
