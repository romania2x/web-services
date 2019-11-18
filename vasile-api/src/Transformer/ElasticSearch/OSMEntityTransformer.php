<?php

namespace App\Transformer\ElasticSearch;

use App\Model\OpenStreetMap\OpenStreetMapEntityInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Transformer\ModelToElasticaTransformerInterface;
use proj4php\Point;
use proj4php\Proj;
use proj4php\Proj4php;

/**
 * Class OSMEntityTransformer
 * @package App\Transformer\ElasticSearch
 */
class OSMEntityTransformer
{
    /**
     * @param OpenStreetMapEntityInterface $osmEntity
     * @return array
     * @throws \exception
     */
    public function transform(OpenStreetMapEntityInterface $osmEntity)
    {
        $geometry = json_decode(\geoPHP::load($osmEntity->getWay())->out('geojson'));
        $proj4 = new Proj4php();

        $source = new Proj('EPSG:3857', $proj4);
        $target = new Proj('EPSG:4326', $proj4);
        switch ($geometry->type) {
            case 'Point':
                $point = new Point($geometry->coordinates[0], $geometry->coordinates[1], $source);
                $transformedPoint = $proj4->transform($target, $point);
                $geometry->coordinates[0] = $transformedPoint->toArray()[0];
                $geometry->coordinates[1] = $transformedPoint->toArray()[1];
                break;
            case 'Line':
            case 'LineString':
                foreach ($geometry->coordinates as $index => $pair) {
                    $point = new Point($pair[0], $pair[1], $source);
                    $transformedPoint = $proj4->transform($target, $point);
                    $pair[0] = $transformedPoint->toArray()[0];
                    $pair[1] = $transformedPoint->toArray()[1];
                    $geometry->coordinates[$index] = $pair;
                }
                break;
            case 'Polygon':
                foreach ($geometry->coordinates as $setIndex => $set) {
                    foreach ($set as $pairIndex => $pair) {
                        $point = new Point($pair[0], $pair[1], $source);
                        $transformedPoint = $proj4->transform($target, $point);
                        $pair[0] = $transformedPoint->toArray()[0];
                        $pair[1] = $transformedPoint->toArray()[1];
                        $geometry->coordinates[$setIndex][$pairIndex] = $pair;
                    }
                }
                break;
            default:
                dd($geometry->type);
        }

        $properties = (array)$osmEntity;
        unset($properties['osmId']);
        unset($properties['way']);
        $properties['geometry'] = $geometry;
        $properties['class'] = get_class($osmEntity);

        return [
            'index' => 'osm',
            'id' => $osmEntity->getOsmId(),
            'body' => $properties
        ];
    }
}
