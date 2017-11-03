<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\DependencyInjection;

use Loevgaard\DandomainAltapayBundle\DependencyInjection\LoevgaardDandomainAltapayExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class LoevgaardDandomainAltapayExtensionTest extends TestCase
{
    public function testThrowsExceptionUnlessAltapayUsernameSet()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['altapay_username']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testThrowsExceptionUnlessAltapayPasswordSet()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['altapay_password']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testThrowsExceptionUnlessSharedKey1Set()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['shared_key_1']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testThrowsExceptionUnlessSharedKey2Set()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['shared_key_2']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testThrowsExceptionUnlessAltapayIpsSet()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['altapay_ips']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testThrowsExceptionUnlessDefaultSettingsSet()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['default_settings']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testGettersSetters()
    {
        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        $container = new ContainerBuilder();
        $loader->load([$config], $container);

        $this->assertSame($config['altapay_username'], $container->getParameter('loevgaard_dandomain_altapay.altapay_username'));
        $this->assertSame($config['altapay_password'], $container->getParameter('loevgaard_dandomain_altapay.altapay_password'));
        $this->assertSame($config['altapay_url'], $container->getParameter('loevgaard_dandomain_altapay.altapay_url'));
        $this->assertSame($config['altapay_ips'], $container->getParameter('loevgaard_dandomain_altapay.altapay_ips'));
        $this->assertSame($config['shared_key_1'], $container->getParameter('loevgaard_dandomain_altapay.shared_key_1'));
        $this->assertSame($config['shared_key_2'], $container->getParameter('loevgaard_dandomain_altapay.shared_key_2'));
        $this->assertSame($config['webhook_urls'], $container->getParameter('loevgaard_dandomain_altapay.webhook_urls'));
        $this->assertSame($config['default_settings'], $container->getParameter('loevgaard_dandomain_altapay.default_settings'));
    }

    /**
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
altapay_username: username
altapay_password: password
altapay_url: https://altapayurl.com
altapay_ips: ['123.123.123.123']
shared_key_1: key1
shared_key_2: key2
webhook_urls:
    ['http://www.example.com']
default_settings:
    layout:
        logo: logo_default.jpg
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
