<?php

namespace App\Entity\OpenStreetMap;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SpatialReferenceSystem
 * @package App\Entity\OpenStreetMap
 * @ORM\Entity()
 * @ORM\Table(name="spatial_ref_sys")
 */
class SpatialReferenceSystem
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    public $srid;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $authName;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    public $authSrid;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $srtext;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    public $proj4text;
}
