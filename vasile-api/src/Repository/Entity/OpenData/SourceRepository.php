<?php
declare(strict_types = 1);

namespace App\Repository\Entity\OpenData;

use App\Entity\OpenData\Source;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @param bool|null $downloadable
     * @param array|null $tags
     * @param Source|null $parent
     * @return Source
     * @throws \Exception
     */
    public function createOrUpdate(
        string $url,
        string $title,
        ?string $description = null,
        ?bool $downloadable = false,
        ?array $tags = [],
        ?Source $parent = null
    ): Source {
        /** @var Source $source */
        if ($source = $this->findOneBy(['url' => $url])) {
            $source
                ->setTitle($title)
                ->setDescription($description)
                ->setDownloadable($downloadable)
                ->setTags($tags);
        } else {
            $source = new Source();
            $source
                ->setUrl($url)
                ->setTitle($title)
                ->setDescription($description)
                ->setDownloadable($downloadable)
                ->setTags($tags);
        }

        if ($parent) {
            $source->setParent($parent);
        }

        $this->entityManager->persist($source);
        $this->entityManager->flush();

        return $source;
    }

    /**
     * @param string $tag
     * @return array|Source[]
     * @throws \Exception
     */
    public function findDownloadableResourcesByTag(string $tag): array
    {
        $query = $this->entityManager->createQuery(<<<EOL
            match (s:Source{downloadable:true}) where single(tag in s.tags where tag = {tag}) return s
EOL
        );
        $query->addEntityMapping('s', Source::class);
        $query->setParameter('tag', $tag);

        return (array) $query->execute();
    }
}
