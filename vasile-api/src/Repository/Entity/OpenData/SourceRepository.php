<?php
declare(strict_types = 1);

namespace App\Repository\Entity\OpenData;

use App\Entity\OpenData\Source;
use App\Repository\Entity\AbstractOGMRepository;

/**
 * Class SourceRepository
 * @package App\Repository\Entity\OpenData
 */
class SourceRepository extends AbstractOGMRepository
{
    /**
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return Source::class;
    }

    /**
     * @param string      $url
     * @param string      $type
     * @param string      $title
     * @param string|null $description
     * @param bool|null   $downloadable
     * @param Source|null $parent
     * @return Source
     * @throws \Exception
     */
    public function createOrUpdate(
        string $url,
        string $type,
        string $title,
        ?string $description = null,
        ?bool $downloadable = false,
        ?Source $parent = null
    ): Source {
        $source = $this->findOneBy(['url' => $url]) ?? new Source();

        $source
            ->setUrl($url)
            ->setTitle($title)
            ->setDescription($description)
            ->setDownloadable($downloadable)
            ->setType($type);

        if ($parent) {
            $source->setParent($parent);
        }

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }

    /**
     * @param string $type
     * @return array|Source[]
     * @throws \Exception
     */
    public function findDownloadableResourcesByType(string $type): array
    {
        $query = $this->entityManager->createQuery(<<<EOL
            match (s:Source{downloadable:true}) where type = {type} return s
EOL
        );
        $query->addEntityMapping('s', Source::class);
        $query->setParameter('type', $type);

        return (array) $query->execute();
    }
}
