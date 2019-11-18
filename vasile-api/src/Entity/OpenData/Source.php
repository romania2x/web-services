<?php
declare(strict_types = 1);

namespace App\Entity\OpenData;

use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * Class Source
 * @package App\Entity\OpenData
 *
 * @OGM\Node(label="Source")
 */
class Source
{
    /**
     * @var int
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    protected $title;

    /**
     * @var string|null
     * @OGM\Property(type="string")
     */
    protected $description;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    protected $url;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    protected $type;

    /**
     * @var bool
     * @OGM\Property(type="boolean")
     */
    protected $downloadable = false;
    /**
     * @var Source
     * @OGM\Relationship(type="PARENT", direction="INCOMING", collection=false, mappedBy="children",
     *                                    targetEntity="Source")
     */
    protected $parent;

    /**
     * @var Source[]|Collection
     * @OGM\Relationship(type="PARENT", direction="OUTGOING", collection=true, mappedBy="parent",
     *                                    targetEntity="Source")
     */
    protected $children;

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
     * @param int $id
     * @return Source
     */
    public function setId(int $id): Source
    {
        $this->id = $id;
        return $this;
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

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Source
     */
    public function setType(string $type): Source
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDownloadable(): bool
    {
        return $this->downloadable;
    }

    /**
     * @param bool $downloadable
     * @return Source
     */
    public function setDownloadable(bool $downloadable): Source
    {
        $this->downloadable = $downloadable;
        return $this;
    }
}
