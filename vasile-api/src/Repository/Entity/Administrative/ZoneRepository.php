<?php

namespace App\Repository\Entity\Administrative;

use App\Entity\Administrative\Zone;
use App\Repository\Entity\AbstractOGMRepository;

/**
 * Class ZoneRepository
 * @package App\Repository\Entity\Administrative
 */
class ZoneRepository extends AbstractOGMRepository
{
    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return Zone::class;
    }

    /**
     * @param Zone $zone
     * @return Zone
     * @throws \Exception
     */
    public function createOrUpdate(Zone $zone): Zone
    {
        /** @var Zone $existingZone */
        if ($existingZone = $this->findOneBy(['nationalId' => $zone->getNationalId()])) {
            $existingZone
                ->setName($zone->getName())
                ->setSlug($zone->getSlug())
                ->setNationalId($zone->getNationalId())
                ->setSiruta($zone->getSiruta());
            $zone = $existingZone;
        }

        $this->entityManager->persist($zone);
        $this->entityManager->flush();

        return $zone;
    }
}
