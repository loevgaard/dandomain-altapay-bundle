<?php
namespace Loevgaard\DandomainAltapayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LoevgaardDandomainAltapayExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('loevgaard_dandomain_altapay.altapay_username', $config['altapay_username']);
        $container->setParameter('loevgaard_dandomain_altapay.altapay_password', $config['altapay_password']);
        $container->setParameter('loevgaard_dandomain_altapay.shared_key_1', $config['shared_key_1']);
        $container->setParameter('loevgaard_dandomain_altapay.shared_key_2', $config['shared_key_2']);
        $container->setParameter('loevgaard_dandomain_altapay.terminal_class', $config['terminal_class']);
        $container->setParameter('loevgaard_dandomain_altapay.payment_class', $config['payment_class']);
        $container->setParameter('loevgaard_dandomain_altapay.order_line_class', $config['order_line_class']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
