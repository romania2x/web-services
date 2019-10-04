<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class User
 * @package App\Document
 *
 * @ODM\Document()
 * @Serializer\ExclusionPolicy("ALL")
 */
class User
{
    /**
     * @var string
     * @ODM\Id()
     */
    public $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Serializer\Expose()
     */
    public $firstName;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Serializer\Expose()
     */
    public $lastName;

    /**
     * @var string
     * @ODM\Field(type="string")
     * @Serializer\Expose()
     */
    public $email;
}
