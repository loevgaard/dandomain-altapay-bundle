<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Provider;

use Loevgaard\DandomainAltapayBundle\Entity\SiteSetting;
use Loevgaard\DandomainAltapayBundle\Entity\SiteSettingRepository;
use Loevgaard\DandomainAltapayBundle\Provider\SiteSettingsProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class SiteSettingsProviderTest extends TestCase
{
    public function testOnlyDefaultSettings()
    {
        $container = $this->getContainer();
        $repository = $this->getRepository();

        $repository->method('findBySiteIdIndexedBySetting')->willReturn(null);

        $expected = array_map(function () {
            return 'default_setting';
        }, SiteSetting::getSettings());

        $siteSettingsProvider = new SiteSettingsProvider($container, $repository);
        $settings = $siteSettingsProvider->findBySiteIdIndexedBySetting(26);

        $this->assertSame($expected, $settings);
    }

    public function testOneSetting()
    {
        $container = $this->getContainer();
        $repository = $this->getRepository();

        $siteSetting = new SiteSetting();
        $siteSetting->setVal('11223344')->setSetting(SiteSetting::SETTING_PHONE)->setSiteId(26);

        $repository->method('findBySiteIdIndexedBySetting')->willReturn([SiteSetting::SETTING_PHONE => $siteSetting]);

        $expected = array_map(function () {
            return 'default_setting';
        }, SiteSetting::getSettings());
        $expected['phone'] = $siteSetting;

        $siteSettingsProvider = new SiteSettingsProvider($container, $repository);
        $settings = $siteSettingsProvider->findBySiteIdIndexedBySetting(26);

        $this->assertEquals($expected, $settings);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    private function getContainer()
    {
        /** @var ContainerInterface|\PHPUnit_Framework_MockObject_MockObject $container */
        $container = $this->getMockForAbstractClass(ContainerInterface::class);

        $container->method('getParameter')->willReturn('default_setting');

        return $container;
    }

    /**
     * @return SiteSettingRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getRepository()
    {
        /** @var SiteSettingRepository|\PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this->getMockBuilder(SiteSettingRepository::class)->disableOriginalConstructor()->getMock();

        return $repository;
    }
}
