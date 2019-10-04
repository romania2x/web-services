<?php

namespace App\Entity;

use GraphAware\Neo4j\OGM\Annotations as Graph;

/**
 * Class Project
 * @package App\Entity
 *
 * @Graph\Node(label="Project")
 */
class Project
{
    /**
     * @var string
     * @Graph\GraphId()
     */
    public $id;
}
