<?php

namespace Loevgaard\DandomainAltapayBundle\DependencyInjection;

use Loevgaard\DandomainAltapayBundle\Entity\SiteSetting;
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
        $container->setParameter('loevgaard_dandomain_altapay.altapay_ips', $config['altapay_ips']);
        $container->setParameter('loevgaard_dandomain_altapay.altapay_url', $config['altapay_url']);
        $container->setParameter('loevgaard_dandomain_altapay.shared_key_1', $config['shared_key_1']);
        $container->setParameter('loevgaard_dandomain_altapay.shared_key_2', $config['shared_key_2']);
        $container->setParameter('loevgaard_dandomain_altapay.cookie_payment_id', $config['cookie_payment_id']);
        $container->setParameter('loevgaard_dandomain_altapay.cookie_checksum_complete', $config['cookie_checksum_complete']);
        $container->setParameter('loevgaard_dandomain_altapay.webhook_urls', $config['webhook_urls']);
        $container->setParameter('loevgaard_dandomain_altapay.default_settings', $config['default_settings']);

        // set individual default settings
        $container->setParameter('loevgaard_dandomain_altapay.default_settings.layout.logo', $config['default_settings']['layout']['logo']);

        $this->verifyDefaultSettings($container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * This method will verify that all available settings in the SiteSetting are defined as parameters.
     *
     * @param ContainerBuilder $container
     *
     * @throws \RuntimeException
     */
    private function verifyDefaultSettings(ContainerBuilder $container)
    {
        $settings = SiteSetting::getSettings();
        foreach ($settings as $setting) {
            $parameter = 'loevgaard_dandomain_altapay.default_settings.'.$setting;
            if (!$container->hasParameter($parameter)) {
                throw new \RuntimeException('The parameter `'.$parameter.'` is not configured in the code');
            }
        }
    }
}
