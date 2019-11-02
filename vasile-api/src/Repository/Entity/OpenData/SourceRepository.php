<?php
declare(strict_types = 1);

namespace App\Repository\Entity\OpenData;

use App\Entity\OpenData\Source;
use GraphAware\Neo4j\OGM\Repository\BaseRepository;

/**
 * Class SourceRepository
 * @package App\Repository\Entity\OpenData
 */
class SourceRepository extends BaseRepository
{
    /**
     * @param string $url
     * @param string $title
     * @param string|null $description
     * @param Source|null $parent
     * @return Source
     * @throws \Exception
     */
    public function createOrUpdate(
        string $url,
        string $title,
        ?string $description = null,
        ?Source $parent = null
    ): Source {
        /** @var Source $source */
        if ($source = $this->findOneBy(['url' => $url])) {
            $source
                ->setTitle($title)
                ->setDescription($description);
        } else {
            $source = new Source();
            $source
                ->setUrl($url)
                ->setTitle($title)
                ->setDescription($description);
        }

        if ($parent) {
            $source->setParent($parent);
        }

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }
}
