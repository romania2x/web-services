<?php
declare(strict_types = 1);

namespace App\Repository\OpenData;

use App\Entity\OpenData\Source;
use App\Repository\AbstractRepository;
use GraphAware\Neo4j\OGM\Repository\BaseRepository;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * Class SourceRepository
 * @package App\Repository\Entity\OpenData
 */
class SourceRepository extends AbstractRepository
{
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
        $source = $this->graphEntityManager->getRepository(Source::class)->findOneBy(['url' => $url]) ?? new Source();

        $source
            ->setUrl($url)
            ->setTitle($title)
            ->setDescription($description)
            ->setDownloadable($downloadable)
            ->setType($type);

        if ($parent) {
            $source->setParent($parent);
        }

        $this->graphEntityManager->persist($source);
        $this->graphEntityManager->flush();

        return $source;
    }

    /**
     * @return BaseRepository
     */
    public function getOGMRepository(): BaseRepository
    {
        return $this->graphEntityManager->getRepository(Source::class);
    }
}
