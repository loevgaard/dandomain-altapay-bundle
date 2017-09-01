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
            ->fixXmlConfig('altapay_ip')
            ->children()
                ->scalarNode('altapay_username')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('altapay_password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('altapay_ips')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()
                ->scalarNode('shared_key_1')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('shared_key_2')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('terminal_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('callback_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
