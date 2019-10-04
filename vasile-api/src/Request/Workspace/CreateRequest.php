<?php

namespace App\Request\Workspace;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateRequest
 * @package App\Request\Workspace
 */
class CreateRequest
{
    /**
     * @Assert\NotBlank()
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @Serializer\Type("string")
     */
    public $description;
}
