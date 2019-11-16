<?php

declare(strict_types = 1);

namespace App\Repository\Entity\Administrative;

use App\Entity\Administrative\AdministrativeUnit;
use App\Entity\Administrative\Way;
use App\Repository\Entity\AbstractOGMRepository;

/**
 * Class WayRepository
 * @package App\Repository\Entity\Administrative
 */
class WayRepository extends AbstractOGMRepository
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
     * @param Way                $way
     * @param AdministrativeUnit $administrativeUnit
     * @return Way
     * @throws \Exception
     */
    public function createOrUpdate(Way $way, AdministrativeUnit $administrativeUnit): Way
    {
        /** @var Way $existingWay */
        $existingWay = $this->findOneBy(
            [
                'countyId'           => $way->getCountyId(),
                'administrativeUnit' => $administrativeUnit->getId()
            ]
        );

        if (!is_null($existingWay)) {
            $existingWay
                ->setName($way->getName() ?? $existingWay->getName())
                ->setTitle($way->getTitle() ?? $existingWay->getTitle())
                ->setSlug($way->getSlug() ?? $existingWay->getSlug())
                ->setCountyId($way->getCountyId() ?? $existingWay->getCountyId());


            $way = $existingWay;
        }
        $way->setAdministrativeUnit($administrativeUnit);
        $this->persist($way, true);

        return $way;
    }

    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return Way::class;
    }
}
