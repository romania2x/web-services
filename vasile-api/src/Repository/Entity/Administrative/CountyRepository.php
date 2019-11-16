<?php

declare(strict_types = 1);

namespace App\Repository\Entity\Administrative;

use App\Entity\Administrative\County;
use App\Entity\Administrative\Zone;
use App\Repository\Entity\AbstractOGMRepository;

/**
 * Class CountyRepository
 * @package App\Repository\Entity\Administrative
 */
class CountyRepository extends AbstractOGMRepository
{
    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return County::class;
    }

    /**
     * @param County $county
     * @param Zone   $zone
     * @return County
     * @throws \Exception
     */
    public function createOrUpdate(County $county, Zone $zone): County
    {
        /** @var County $existingCounty */
        if ($existingCounty = $this->findOneBy(['nationalId' => $county->getNationalId()])) {
            $existingCounty
                ->setMnemonic($county->getMnemonic())
                ->setName($county->getName())
                ->setSlug($county->getSlug())
                ->setSortingIndex($county->getSortingIndex());
            $county = $existingCounty;
        }

        $county->setZone($zone);

        $this->entityManager->persist($county);
        $this->entityManager->flush();

        return $county;
    }
}
