<?php

namespace App\Repository\Administrative;

use App\Model\Administrative\WayNumber;
use App\Repository\AbstractRepository;

class WayNumberRepository extends AbstractRepository
{
    /**
     * @param \SimpleXMLElement $row
     * @param int               $parentUnitId
     */
    public function createFromStreets(\SimpleXMLElement $row, int $parentUnitId)
    {
        $start = intval($row->NUMAR_START);
        $end   = intval($row->NUMAR_SFARSIT);

        if ($end > 500) {
            $end = 500;
        }

        if ($end == 0 && $start > 0) {
            $end = $start;
        }

        if ($start == 0 || $end == 0) {
            dd($row);
        }

        for ($counter = $start; $counter <= $end; $counter += 2) {
            $this->neo4jClient->run(
                $query = <<<EOT
                match (pu:Administrative)-[:PARENT]->(w:Way{countyId:{wayCountyId}}) where id(pu) = {parentUnitId}
                merge (wn:WayNumber:Administrative{number:{wayNo}})<-[:PARENT]-(w)
                on create set wn = {wayNumber}
                on match set wn += {wayNumber}
                return id(wn) as id
EOT
                ,
                [
                    'parentUnitId' => $parentUnitId,
                    'wayCountyId'  => intval($row->COD),
                    'wayNo'        => $counter,
                    'wayNumber'    => [
                        'number'     => $counter,
                        'postalCode' => $row->COD_POSTAL
                    ]
                ]
            );
        }
    }
}
