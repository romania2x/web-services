<?php

namespace App\Constants\Administrative;

/**
 * Class UnitType
 * @package App\Constants\Administrative
 */
abstract class UnitType
{
    const COUNTY = 40;
    const MUNICIPALITY_WITH_COUNTY_SEAT = 1;
    const MUNICIPALITY = 4;
    const CITY = 2;
    const CITY_WITH_COUNTY_SEAT = 5;
    const SECTOR_OF_BUCHAREST_MUNICIPALITY = 6;
    const COMMUNE = 3;
    const LOCALITY_OF_MUNICIPALITY_WITH_COUNTY_SEAT = 9;
    const LOCALITY_OF_MUNICIPALITY = 10;
    const LOCALITY_OF_CITY_WITH_COUNTY_SEAT = 17;
    const LOCALITY_OF_CITY = 18;
    const VILLAGE_OF_MUNICIPALITY = 11;
    const VILLAGE_OF_CITY = 19;
    const VILLAGE_OF_COMMUNE_WITH_SEAT = 22;
    const VILLAGE_OF_COMMUNE = 23;
}
