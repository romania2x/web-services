<?php

namespace App\Controller;

use App\Cache\OSMPropertiesCache;
use App\Entity\OpenStreetMap\OpenStreetMapEntityInterface;
use App\Entity\OpenStreetMap\PlanetLine;
use App\Entity\OpenStreetMap\PlanetPoint;
use App\Entity\OpenStreetMap\PlanetPolygon;
use App\Entity\OpenStreetMap\PlanetRoad;
use App\Repository\Entity\OpenStreetMap\PlanetRepository;
use App\Request\OpenStreetMap\SearchFeaturesRequest;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\Query\QueryException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class OSMController
 * @package App\Controller
 * @Rest\Route("/osm")
 */
class OSMController extends AbstractController
{
    const TABLE_MAPPINGS = [
        'line' => PlanetLine::class,
        'polygon' => PlanetPolygon::class,
        'point' => PlanetLine::class,
        'road' => PlanetRoad::class
    ];

    /**
     * @param SearchFeaturesRequest $request
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return mixed
     *
     * @Rest\Post("/layers")
     * @Rest\View()
     *
     *
     * @ParamConverter("request", converter="fos_rest.request_body")
     *
     * @throws DBALException
     */
    public function searchFeatures(
        SearchFeaturesRequest $request,
        ConstraintViolationListInterface $validationErrors
    ) {

        if (count($validationErrors)) {
            return View::create($validationErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $results = [];

        if (!is_null($request->getLine())) {
            $results = $this->getDoctrine()->getRepository(PlanetLine::class)->findFeaturesByProperties($request->getLine());
        }
        if (!is_null($request->getPoint())) {
            $results = array_merge(
                $results,
                $this->getDoctrine()->getRepository(PlanetPoint::class)->findFeaturesByProperties($request->getPoint())
            );
        }
        if (!is_null($request->getPolygon())) {
            $results = array_merge(
                $results,
                $this->getDoctrine()->getRepository(PlanetPolygon::class)->findFeaturesByProperties($request->getPolygon())
            );
        }
        if (!is_null($request->getRoad())) {
            $results = array_merge(
                $results,
                $this->getDoctrine()->getRepository(PlanetRoad::class)->findFeaturesByProperties($request->getRoad())
            );
        }

        return $results;
    }

    /**
     * @param OSMPropertiesCache $cache
     *
     * @Rest\Get("/layers")
     * @return mixed
     *
     */
    public function layers(OSMPropertiesCache $cache)
    {
        $layers = self::TABLE_MAPPINGS;

        foreach ($layers as $layerName => $className) {
            if ($cachedProperties = $cache->get($layerName)) {
                $layers[$layerName] = $cachedProperties;
            } else {
                /** @var PlanetRepository $repository */
                $repository = $this->getEntityManager()->getRepository(self::TABLE_MAPPINGS[$layerName]);

                $layers[$layerName] = $repository->findPropertiesDefinitions();

                $cache->set($layerName, $layers[$layerName]);
            }

        }

        return $layers;
    }
}
