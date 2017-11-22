<?php

namespace Loevgaard\DandomainAltapayBundle\Provider;

use Loevgaard\DandomainAltapayBundle\Entity\SiteSetting;
use Loevgaard\DandomainAltapayBundle\Entity\SiteSettingRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SiteSettingsProvider
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var SiteSettingRepository
     */
    protected $siteSettingRepository;

    public function __construct(ContainerInterface $container, SiteSettingRepository $siteSettingRepository)
    {
        $this->container = $container;
        $this->siteSettingRepository = $siteSettingRepository;
    }

    public function findBySiteIdIndexedBySetting(int $siteId)
    {
        $availableSettings = SiteSetting::getSettings();

        // set default settings
        $defaultSettings = [];
        foreach ($availableSettings as $availableSetting) {
            $defaultSettings[$availableSetting] = $this->container
                ->getParameter('loevgaard_dandomain_altapay.default_settings.'.$availableSetting);
        }

        // fetch settings and set default settings if not set
        $settings = $this->siteSettingRepository->findBySiteIdIndexedBySetting($siteId);
        if (!empty($settings)) {
            foreach ($availableSettings as $availableSetting) {
                if (!isset($settings[$availableSetting])) {
                    $settings[$availableSetting] = $defaultSettings[$availableSetting];
                }
            }
        } else {
            $settings = $defaultSettings;
        }

        return $settings;
    }
}
