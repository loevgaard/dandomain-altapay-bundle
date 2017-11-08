<?php

namespace Loevgaard\DandomainAltapayBundle\Tests\Entity;

use Loevgaard\DandomainAltapayBundle\Entity\SiteSetting;
use PHPUnit\Framework\TestCase;

class SiteSettingTest extends TestCase
{
    public function testGettersSetters()
    {
        $siteSetting = new SiteSetting();

        $siteSetting
            ->setId(1)
            ->setSetting(SiteSetting::SETTING_LAYOUT_LOGO)
            ->setSiteId(26)
            ->setVal('value')
        ;

        $this->assertSame(1, $siteSetting->getId());
        $this->assertSame(SiteSetting::SETTING_LAYOUT_LOGO, $siteSetting->getSetting());
        $this->assertSame(26, $siteSetting->getSiteId());
        $this->assertSame('value', $siteSetting->getVal());
        $this->assertSame('value', (string)$siteSetting);
    }
}
