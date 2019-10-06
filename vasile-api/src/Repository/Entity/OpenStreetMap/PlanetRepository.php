<?php

namespace App\Repository\Entity\OpenStreetMap;

use App\Entity\OpenStreetMap\OpenStreetMapEntityInterface;
use CrEOF\Spatial\PHP\Types\Geometry\LineString;
use Doctrine\ORM\EntityRepository;

/**
 * Class PlanetRepository
 * @package App\Repository\Entity\OpenStreetMap
 */
class PlanetRepository extends EntityRepository
{
    public function count(array $criteria = [])
    {
        return parent::count($criteria);
    }

    /**
     * Get distinct values for column
     * @param $columnName
     * @return mixed
     */
    public function findDistinctValuesByColumnName($columnName)
    {
        $query = $this->createQueryBuilder('pl')
            ->select("pl.{$columnName}")
            ->distinct()
            ->getQuery();

        return array_map(function ($result) {
            return array_values($result)[0];
        }, $query->getResult());
    }


    /**
     * @return array
     */
    public function findPropertiesDefinitions()
    {
        $properties = array_map(
            [$this, 'createPropertyDefinition'],
            array_keys(get_class_vars($this->getClassName()))
        );

        return array_values(array_filter($properties));
    }


    /**
     * @param array $properties
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findFeaturesByProperties(array $properties)
    {
        $criteria = ["true"];

        foreach ($properties as $key => $value) {
            $definition = $this->createPropertyDefinition($key);
            if (is_null($definition)) {
                continue;
            }
            switch ($definition['type']) {
                case 'query':
                    $criteria[] = "lower(unaccent({$key})) like unaccent(lower('{$value}'))";
                    break;
                case 'options':
                    $value = implode("','", $value);
                    $criteria[] = "{$key} in ('{$value}}')";
                    break;
            }
        }

        $criteria = implode(' AND ', $criteria);

        $query = $this->getEntityManager()->getConnection()->prepare(
            $nativeQuery = <<<EOLSQL
            select *, ST_AsGeoJSON(ST_Transform(way, 4326)) as geometry
            from {$this->getClassMetadata()->getTableName()}
            where {$criteria} limit 10000
EOLSQL
        );

        $query->execute();

        $features = array_map(function ($feature) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($feature['geometry'], true),
                'properties' => $feature
            ];

            unset($feature['properties']['way']);
            unset($feature['properties']['geometry']);

            return $feature;
        }, $query->fetchAll());

        return $features;
    }

    /**
     * Private helper to classify properties
     * @param $propertyName
     * @return array|null
     */
    private function createPropertyDefinition($propertyName)
    {
        switch ($propertyName) {
            case 'way':
                return null;
            case 'osmId':
            case 'name':
            case 'operator':
            case 'population':
            case 'ref':
            case 'width':
            case 'zOrder':
            case 'wayArea':
                return ['name' => $propertyName, 'type' => 'query'];
            default:
                // todo: caching of distinct values
                return [
                    'name' => $propertyName,
                    'type' => 'options',
                    'options' => $this->findDistinctValuesByColumnName($propertyName)
                ];
        }
    }
}
