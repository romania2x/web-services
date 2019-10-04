<?php

namespace App\Entity\OpenStreetMap;

interface OpenStreetMapEntityInterface
{
    public function getWay();

    public function setWay($way);

    public function getOsmId();
}
