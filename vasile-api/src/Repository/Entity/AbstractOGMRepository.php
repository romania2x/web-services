<?php

namespace App\Repository\Entity;

use GraphAware\Neo4j\OGM\EntityManagerInterface;
use GraphAware\Neo4j\OGM\Repository\BaseRepository;

/**
 * Class AbstractOGMRepository
 * @package App\Repository\Entity
 */
abstract class AbstractOGMRepository extends BaseRepository
{
    /**
     * AbstractOGMRepository constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct(
            $manager->getClassMetadataFor($this->getEntityClassName()),
            $manager,
            $this->getEntityClassName());
    }

    /**
     * @param      $entity
     * @param bool $flush
     * @throws \Exception
     */
    public function persist($entity, bool $flush = false)
    {
        $this->entityManager->persist($entity);
        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    abstract protected function getEntityClassName(): string;
}
