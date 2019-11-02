<?php
declare(strict_types = 1);

namespace App\Entity\OpenData;

use GraphAware\Neo4j\OGM\Annotations as Graph;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * Class Source
 * @package App\Entity\OpenData
 *
 * @Graph\Node(label="Source", repository="App\Repository\Entity\OpenData\SourceRepository")
 */
class Source
{
    /**
     * @var int
     * @Graph\GraphId()
     */
    private $id;

    /**
     * @var string
     * @Graph\Property(type="string")
     */
    private $title;

    /**
     * @var string|null
     * @Graph\Property(type="string")
     */
    private $description;

    /**
     * @var string
     * @Graph\Property(type="string")
     */
    private $url;

    /**
     * @var Source
     * @Graph\Relationship(type="PARENT", direction="INCOMING", collection=false, mappedBy="children", targetEntity="Source")
     */
    private $parent;

    /**
     * @var Source[]|Collection
     * @Graph\Relationship(type="PARENT", direction="OUTGOING", collection=true, mappedBy="parent", targetEntity="Source")
     */
    private $children;

    /**
     * Source constructor.
     */
    public function __construct()
    {
        $this->children = new Collection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Source
     */
    public function setTitle(string $title): Source
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Source
     */
    public function setDescription(?string $description): Source
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Source
     */
    public function setUrl(string $url): Source
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return Source
     */
    public function getParent(): Source
    {
        return $this->parent;
    }

    /**
     * @param Source $parent
     * @return Source
     */
    public function setParent(Source $parent): Source
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Source[]|Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Source[]|Collection $children
     * @return Source
     */
    public function setChildren($children): Source
    {
        $this->children = $children;
        return $this;
    }
}
