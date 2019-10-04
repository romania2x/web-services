<?php

namespace App\Controller;

use App\Document\User;
use App\Entity\Project;
use App\Mercure\MercureClient;
use App\Message\HeartbeatMessage;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{

    /**
     * @Rest\Get("/")
     * @Rest\View()
     *
     * @return mixed
     */
    public function default()
    {
        return ['messages' => 'Welcome to VasileAPI v0.1'];
    }

    /**
     * @param MercureClient $mercureClient
     *
     * @Rest\Get("/healthcheck")
     * @Rest\View()
     *
     * @return mixed
     */
    public function healthCheck()
    {
        $results = [];
        $tests = [
            'pgsql' => function () {
                $this->getEntityManager()->getConnection()->ping();
            },
            'mongodb' => function () {
                $this->getDocumentManager()->getRepository(User::class)->findAll();
            },
            'neo4j' => function () {
                $this->getGraphEntityManager()->getRepository(Project::class)->findAll();
            },
            'message_bus' => function () {
                $this->getMessageBus()->dispatch(new HeartbeatMessage());
            },
            'geos' => function () {
                if (!\geoPHP::geosInstalled()) {
                    throw new \RuntimeException('Missing php geos extension');
                }
            },
            'elasticsearch' => function () {
                $this->getElasticSearchClient()->index([
                    'index' => 'my_index',
                    'id' => 'my_id',
                    'body' => ['testField' => 'abc']
                ]);
            },
            'mercure' => function () {
                $this->getMercureClient()->update('test', 'Lorem');

            }
        ];
        $atLeastOneError = false;

        foreach ($tests as $testName => $test) {
            try {
                $test();
                $results[] = ['name' => $testName, 'state' => true];
            } catch (\Exception $exception) {
                $atLeastOneError = true;
                $results[] = [
                    'name' => $testName,
                    'state' => false,
                    'error' => [
                        'class' => get_class($exception),
                        'message' => $exception->getMessage()
                    ]
                ];
            }
        }

        return View::create($results, $atLeastOneError ? Response::HTTP_INTERNAL_SERVER_ERROR : Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/users")
     * @Rest\View()
     *
     * @return object[]
     */
    public function getUsers()
    {
        return $this->getDocumentManager()->getRepository(User::class)->findAll();
    }
}
