<?php
declare(strict_types = 1);

namespace App\Entity\Traits;

use App\Entity\OpenData\Source;
use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * Trait SourceAwareTrait
 * @package App\Entity\Traits
 */
trait SourceAwareTrait
{
    /**
     * @var array
     * @OGM\Property(type="array")
     */
    protected $sources = [];

    /**
     * @return array
     */
    public function getSources(): array
    {
        return $this->sources;
    }

    /**
     * @param array $sources
     * @return $this
     */
    public function setSources(array $sources): self
    {
        $this->sources = $sources;
        return $this;
    }

    /**
     * @param string $source
     * @return $this
     */
    public function addSource(string $source): self
    {
        if (is_null($this->sources)) {
            $this->sources = [];
        }
        if (!in_array($source, $this->sources)) {
            $this->sources[] = $source;
        }
        return $this;
    }
}
