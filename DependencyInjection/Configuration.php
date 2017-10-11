<?php

namespace Loevgaard\DandomainAltapayBundle\DependencyInjection;

use Assert\Assert;
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
                            return filter_var($v, FILTER_VALIDATE_URL) === false;
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
                ->scalarNode('terminal_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('callback_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('http_transaction_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('payment_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('payment_line_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('cookie_payment_id')
                    ->defaultValue('payment_id')
                ->end()
                ->scalarNode('cookie_checksum_complete')
                    ->defaultValue('checksum_complete')
                ->end()
            ->end()
            ->fixXmlConfig('altapay_ip')
        ;

        return $treeBuilder;
    }
}
