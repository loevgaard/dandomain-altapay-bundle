<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\SiteSetting;
use Loevgaard\DandomainAltapayBundle\Entity\SiteSettingRepository;
use PHPUnit\Framework\TestCase;

class SiteSettingRepositoryTest extends TestCase
{
    public function testReturnNull()
    {
        $siteSettingRepository = $this->getSiteSettingRepository();

        $siteSettingRepository
            ->method('findBy')
            ->willReturn(null);

        $this->assertSame(null, $siteSettingRepository->findBySiteId(26));
    }

    public function testFindBySiteIdAndSetting()
    {
        $siteSettingRepository = $this->getSiteSettingRepository();

        $obj = new SiteSetting();
        $siteSettingRepository
            ->method('findOneBy')
            ->willReturn($obj);

        $this->assertSame($obj, $siteSettingRepository->findBySiteIdAndSetting(26, 'setting'));
    }

    public function testFindBySiteId()
    {
        $siteSettingRepository = $this->getSiteSettingRepository();

        $obj = new SiteSetting();
        $siteSettingRepository
            ->method('findBy')
            ->willReturn([$obj]);

        $this->assertSame([$obj], $siteSettingRepository->findBySiteId(26));
    }

    public function testFindBySetting()
    {
        $siteSettingRepository = $this->getSiteSettingRepository();

        $obj = new SiteSetting();
        $siteSettingRepository
            ->method('findBy')
            ->willReturn([$obj]);

        $this->assertSame([$obj], $siteSettingRepository->findBySetting('setting'));
    }

    /**
     * @return SiteSettingRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getSiteSettingRepository()
    {
        /** @var SiteSettingRepository|\PHPUnit_Framework_MockObject_MockObject $siteSettingRepository */
        $siteSettingRepository = $this->getMockBuilder(SiteSettingRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findOneBy', 'findBy'])
            ->getMock();

        return $siteSettingRepository;
    }
}
