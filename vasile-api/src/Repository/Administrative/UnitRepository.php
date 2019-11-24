<?php

declare(strict_types = 1);

namespace App\Repository\Administrative;

use App\Constants\Administrative\UnitEnvironment;
use App\Constants\Administrative\UnitType;
use App\Helpers\LanguageHelpers;
use App\Model\Administrative\Unit;
use App\Repository\AbstractRepository;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Class UnitRepository
 * @package App\Repository\Administrative
 */
class UnitRepository extends AbstractRepository
{
    /**
     * @param array $row
     * @throws \Exception
     */
    public function createFromSiruta(array $row)
    {
        $unit = new Unit();
        foreach ($row as $key => $value) {
            $value = trim($value);
            switch ($key) {
                case 'DENLOC':
                    $unit->setName(LanguageHelpers::normalizeName($value));
                    $unit->setSlug(LanguageHelpers::slugify($value));
                    break;
                case 'TIP':
                    $unit->setType(UnitType::toString($value));
                    break;
                case 'SIRUTA':
                    $unit->setSiruta(intval($value));
                    break;
                case 'CODP':
                    $postalCode = str_replace(',00', '', $value);
                    if ($postalCode != '0') {
                        $unit->setPostalCode($postalCode);
                    }
                    break;
                case 'MED':
                    $unit->setEnvironment(UnitEnvironment::toString($value));
                    break;
                case 'fictiv':
                    if ($value != '') {
                        $unit->setFictive(intval($value) > 0);
                    }
                    break;
                case 'rang':
                    if ($value != '') {
                        $unit->setRank($value);
                    }
                    break;
                case 'NIV':
                    #depth in structure, we have a graph for that
                case 'SIRSUP':
                case 'JUD':
                case 'REGIUNE':
                    #relational data
                case 'FSJ':
                case 'FS2':
                case 'FS3':
                case 'FSL':
                    //noop
                    break;
                default:
                    throw new \RuntimeException("Unknown field $key with value $value");
            }
        }

        if (UnitType::toString(UnitType::COUNTY) == $unit->getType()) {
            $result = $this->neo4jClient->run(
                <<<EOL
                match (c:County:Administrative{nationalId:{countyNationalId}})
                set c.siruta = {siruta}
                return id(c) as id 
EOL
                ,
                [
                    'countyNationalId' => intval($row['JUD']),
                    'siruta'           => $unit->getSiruta()
                ]
            );
            $result->firstRecord();
        } else {
            $result = $this->neo4jClient->run(
                <<<EOL
                match (pu:Administrative{siruta:{parentSiruta}})
                merge (u:Unit:Administrative{siruta:{siruta}})
                on create set u = {unit}
                on match set u += {unit}
                merge (pu)-[:PARENT]->(u)
                return id(u) as id
EOL
                ,
                [
                    'parentSiruta' => intval($row['SIRSUP']),
                    'siruta'       => $unit->getSiruta(),
                    'unit'         => $this->serializer->toArray($unit)
                ]
            );
            $result->firstRecord();
        }
    }

    /**
     * @param \SimpleXMLElement $row
     * @return Unit|null
     */
    public function updateFromStreetData(\SimpleXMLElement $row): ?Unit
    {
        $unit = new Unit();
        foreach ($row as $key => $value) {
            $value = (string) $value;
            switch ($key) {
                case 'DENUMIRE':
                    $unit->setName(LanguageHelpers::normalizeName($value));
                    $unit->setSlug(LanguageHelpers::slugify($value));
                    break;
                case 'TPL_COD':
                    $unit->setTitle(mb_strtolower($value));
                    break;
                case 'COD_POSTAL':
                    if ($value != '0') {
                        $unit->setPostalCode($value);
                    }
                    break;
                case 'COD_SIRUTA':
                    $unit->setSiruta(intval($value));
                    break;
                case 'ARE_PRIMARIE':
                    $unit->setTownHall($value == 'A');
                    break;
                case 'COD_FISCAL_PRIMARIE':
                    $unit->setTownHallFiscalCode($value);
                    break;
                case 'COD':
                    $unit->setStructuralId(intval($value));
                    break;
                case 'Cod_POLITIE':
                    $unit->setPoliceId(intval($value));
                    break;
                case 'JUD_COD':
                case 'SAR_COD':
                case 'LOC_JUD_COD':
                case 'LOC_COD':
                case 'COD_POLITIE_TATA':
                case 'SAR_COD_MF':
                case 'COD_SIRUTA_TATA':
                    break;
                default:
                    throw new \RuntimeException("Unknown field $key with value $value");
            }
        }


        $result = $this->neo4jClient->run(
            <<<EOL
            match (u:Administrative) where u.siruta = {siruta} or u.postalCode = {postalCode}
            set u += {unit}
            return id(u) as id
EOL
            ,
            [
                'siruta'     => $unit->getSiruta(),
                'postalCode' => $unit->getPostalCode(),
                'unit'       => $this->serializer->toArray($unit)
            ]
        );
        try {
            $unit->setId($result->firstRecord()->get('id'));
            return $unit;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @param int $id
     * @return Unit|null
     */
    public function find(int $id): ?Unit
    {
        return $this->graphEntityManager->getRepository(Unit::class)->find($id);
    }
}
