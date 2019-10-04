<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Yaml\Parser;

/**
 * Class BaseLayersController
 * @package App\Controller
 */
class BaseLayersController extends AbstractController
{
    /**
     * @Rest\Get("/base-layers")
     * @Rest\View()
     */
    public function listBaseLayers()
    {
        $yamlParser = new Parser();
        $mapproxyConfig = $yamlParser->parse(file_get_contents('/configs/mapproxy/mapproxy.yaml'));

        $baseLayers = [];
        foreach ($mapproxyConfig['layers'] as $layer) {
            $grid = $mapproxyConfig['caches'][$layer['sources'][0]]['grids'][0];
            $baseLayers[] = [
                'title' => $layer['title'],
                'url' => "http://public.vasile/mapproxy/tiles/1.0.0/{$layer['name']}/{$grid}/{z}/{x}/{y}.png",
            ];
        }

        return $baseLayers;
    }
}
