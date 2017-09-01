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

    public function testThrowsExceptionUnlessTerminalClassSet()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['terminal_class']);
        $loader->load([$config], new ContainerBuilder());
    }

    public function testThrowsExceptionUnlessCallbackClassSet()
    {
        $this->expectException(InvalidConfigurationException::class);

        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        unset($config['callback_class']);
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

    public function testGettersSetters()
    {
        $loader = new LoevgaardDandomainAltapayExtension();
        $config = $this->getEmptyConfig();
        $container = new ContainerBuilder();
        $loader->load([$config], $container);

        $this->assertSame($config['altapay_username'], $container->getParameter('loevgaard_dandomain_altapay.altapay_username'));
        $this->assertSame($config['altapay_password'], $container->getParameter('loevgaard_dandomain_altapay.altapay_password'));
        $this->assertSame($config['altapay_ips'], $container->getParameter('loevgaard_dandomain_altapay.altapay_ips'));
        $this->assertSame($config['shared_key_1'], $container->getParameter('loevgaard_dandomain_altapay.shared_key_1'));
        $this->assertSame($config['shared_key_2'], $container->getParameter('loevgaard_dandomain_altapay.shared_key_2'));
        $this->assertSame($config['terminal_class'], $container->getParameter('loevgaard_dandomain_altapay.terminal_class'));
        $this->assertSame($config['callback_class'], $container->getParameter('loevgaard_dandomain_altapay.callback_class'));
    }

    /**
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
altapay_username: username
altapay_password: password
altapay_ips: ['123.123.123.123']
shared_key_1: key1
shared_key_2: key2
terminal_class: TerminalClass
callback_class: CallbackClass
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
