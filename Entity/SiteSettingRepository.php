<?php

namespace Loevgaard\DandomainAltapayBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * This repository is implemented using the principles described here:
 * https://www.tomasvotruba.cz/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/
 */
class SiteSettingRepository extends EntityRepository
{
    /**
     * @param int $siteId
     * @param string $setting
     * @return SiteSetting|null
     */
    public function findBySiteIdAndSetting(int $siteId, string $setting) : ?SiteSetting
    {
        /** @var SiteSetting $obj */
        $obj = $this->repository->findOneBy([
            'siteId' => $siteId,
            'setting' => $setting
        ]);

        return $obj;
    }

    /**
     * @param int $siteId
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return SiteSetting[]|null
     */
    public function findBySiteId(int $siteId, array $orderBy = null, int $limit = null, int $offset = null) : ?array
    {
        /** @var SiteSetting[] $objs */
        $objs = $this->repository->findBy([
            'siteId' => $siteId,
        ], $orderBy, $limit, $offset);

        return $objs;
    }

    /**
     * Usage:
     * $collection = SiteSettingRepository::findBySiteIdIndexedBySetting($siteId)
     * echo $collection['setting']->getVal();
     *
     * @param int $siteId
     * @return SiteSetting[]|null
     */
    public function findBySiteIdIndexedBySetting(int $siteId) : ?array
    {
        $qb = $this->repository->createQueryBuilder('s');
        $qb
            ->where($qb->expr()->eq('s.siteId', $siteId))
            ->indexBy('s', 's.setting')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $setting
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return SiteSetting[]|null
     */
    public function findBySetting(string $setting, array $orderBy = null, int $limit = null, int $offset = null) : ?array
    {
        /** @var SiteSetting[] $objs */
        $objs = $this->repository->findBy([
            'setting' => $setting,
        ], $orderBy, $limit, $offset);

        return $objs;
    }
}
