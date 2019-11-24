<?php

declare(strict_types = 1);

namespace App\Repository\Administrative;

use App\Constants\Administrative\UnitType;
use App\Helpers\LanguageHelpers;
use App\Model\Administrative\Unit;
use App\Repository\AbstractRepository;

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
            switch ($key) {
                case 'DENLOC':
                    $unit->setName(LanguageHelpers::normalizeName($value));
                    $unit->setSlug(LanguageHelpers::slugify($value));
                    break;
                case 'TIP':
                    $unit->setType(intval($value));
                    break;
                case 'SIRUTA':
                    $unit->setSiruta(intval($value));
                    break;
                case 'CODP':
                    $postalCode = str_replace(',00', '', $value);
                    $unit->setPostalCode($postalCode);
                    break;
                case 'SIRSUP':
                case 'JUD':
                case 'NIV':
                case 'MED':
                case 'REGIUNE':
                case 'FSJ':
                case 'FS2':
                case 'FS3':
                case 'FSL':
                case 'rang':
                case 'fictiv':
                    //noop
                    break;
                default:
                    throw new \RuntimeException("Unknown field $key with value $value");
            }
        }

        if (UnitType::COUNTY == $unit->getType()) {
            $this->neo4jClient->run(
                <<<EOL
                match (c:County:Administrative{nationalId:{countyNationalId}})
                set c.siruta = {siruta} 
EOL
                ,
                [
                    'countyNationalId' => intval($row['JUD']),
                    'siruta' => $unit->getSiruta()
                ]
            );
        } else {
            $this->neo4jClient->run(
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
                    'siruta' => $unit->getSiruta(),
                    'unit' => $this->serializer->toArray($unit)
                ]
            );
        }
    }

    /**
     * @param array $row
     * @return Unit|null
     */
    public function updateFromStreetData(array $row): ?Unit
    {
        $unit = new Unit();
        foreach ($row as $key => $value) {
            switch ($key) {
                case 'DENUMIRE':
                    $unit->setName(LanguageHelpers::normalizeName($value));
                    $unit->setSlug(LanguageHelpers::slugify($value));
                    break;
                case 'TPL_COD':
                    $unit->setTitle(mb_strtolower($value));
                    break;
                case 'COD_POSTAL':
                    $unit->setPostalCode($value);
                    break;
                case 'COD_SIRUTA':
                    $unit->setSiruta(intval($value));
                    break;
                case 'JUD_COD':
                case 'COD':
                case 'Cod_POLITIE':
                case 'SAR_COD':
                case 'LOC_JUD_COD':
                case 'LOC_COD':
                case 'ARE_PRIMARIE':
                case 'COD_FISCAL_PRIMARIE':
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
            match (u:Administrative) where u.siruta = {siruta} or  u.postalCode = {postalCode}
            set u += {unit}
            return id(u) as id
EOL
            ,
            [
                'siruta' => $unit->getSiruta(),
                'postalCode' => $unit->getPostalCode(),
                'unit' => $this->serializer->toArray($unit)
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
