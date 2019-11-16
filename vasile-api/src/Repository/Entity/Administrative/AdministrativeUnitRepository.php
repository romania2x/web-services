<?php

declare(strict_types = 1);

namespace App\Repository\Entity\Administrative;

use App\Entity\Administrative\AdministrativeUnit;
use App\Repository\Entity\AbstractOGMRepository;

/**
 * Class AdministrativeUnitRepository
 * @package App\Repository\Entity\Administrative
 */
class AdministrativeUnitRepository extends AbstractOGMRepository
{
    /**
     * @var \Redis
     */
    private $cache;

    /**
     * @param \Redis $cache
     */
    public function setCache(\Redis $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param AdministrativeUnit $administrativeUnit
     * @return AdministrativeUnit
     * @throws \Exception
     */
    public function createOrUpdate(AdministrativeUnit $administrativeUnit): AdministrativeUnit
    {
        /** @var AdministrativeUnit $existingUnit */
        if ($existingUnit = $this->getCachedBySiruta($administrativeUnit->getSiruta())) {
            $existingUnit
                ->setName($administrativeUnit->getName() ?? $existingUnit->getName())
                ->setTitle($administrativeUnit->getTitle() ?? $existingUnit->getTitle())
                ->setSlug($administrativeUnit->getSlug() ?? $existingUnit->getSlug())
                ->setSiruta($administrativeUnit->getSiruta() ?? $existingUnit->getSiruta())
                ->setPostalCodes($administrativeUnit->getPostalCodes() ?? $existingUnit->getPostalCodes())
                ->setType($administrativeUnit->getType() ?? $existingUnit->getType());

            $administrativeUnit = $existingUnit;
        }
        $this->persist($administrativeUnit, true);

        return $administrativeUnit;
    }

    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return AdministrativeUnit::class;
    }

    /**
     * @param int $siruta
     * @return AdministrativeUnit
     */
    public function getCachedBySiruta(int $siruta): ?AdministrativeUnit
    {
        $cacheKey    = implode('.', ['administrative_unit.', $siruta]);
        $cachedEntry = $this->cache->get($cacheKey);
        if ($cachedEntry) {
            return $this->find($cachedEntry);
        }

        /** @var AdministrativeUnit $administrativeUnit */
        $administrativeUnit = $this->findOneBy(['siruta' => $siruta]);
        if ($administrativeUnit) {
            $this->cache->set($cacheKey, $administrativeUnit->getId());
        }
        return $administrativeUnit;
    }
}
