<?php

namespace App\Message;

use App\Entity\OpenStreetMap\OpenStreetMapEntityInterface;

/**
 * Class IndexOpenStreetMapEntityMessage
 * @package App\Message
 */
class IndexOpenStreetMapEntityMessage
{
    private $entity;

    /**
     * IndexOpenStreetMapEntityMessage constructor.
     * @param OpenStreetMapEntityInterface $openStreetMapEntity
     */
    public function __construct(OpenStreetMapEntityInterface $openStreetMapEntity)
    {
        $this->entity = $openStreetMapEntity;
    }

    /**
     * @return OpenStreetMapEntityInterface
     */
    public function getEntity(): OpenStreetMapEntityInterface
    {
        return $this->entity;
    }
}
