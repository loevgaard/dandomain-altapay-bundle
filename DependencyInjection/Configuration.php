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
                ->scalarNode('altapay_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->beforeNormalization()
                        ->ifString()
                            ->then(function ($v) {
                                return rtrim($v, '/');
                            })
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return false === filter_var($v, FILTER_VALIDATE_URL);
                        })
                        ->thenInvalid('The URL is invalid')
                    ->end()
                ->end()
                ->scalarNode('shared_key_1')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('shared_key_2')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('cookie_payment_id')
                    ->defaultValue('payment_id')
                ->end()
                ->scalarNode('cookie_checksum_complete')
                    ->defaultValue('checksum_complete')
                ->end()
                ->arrayNode('webhook_urls')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('default_settings')
                    ->isRequired()
                    ->children()
                        ->arrayNode('layout')
                            ->isRequired()
                            ->children()
                                ->scalarNode('logo')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->fixXmlConfig('webhook_url')
            ->fixXmlConfig('altapay_ip')
        ;

        return $treeBuilder;
    }
}
