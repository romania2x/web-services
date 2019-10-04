<?php

namespace App\Controller;

use App\Document\Workspace;
use App\Request\Workspace\CreateRequest;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class WorkspaceController
 * @package App\Controller
 *
 * @Rest\Route("/workspace")
 */
class WorkspaceController extends AbstractController
{
    /**
     * @Rest\Get()
     * @Rest\View()
     */
    public function list()
    {
        return $this->getDocumentManager()->getRepository(Workspace::class)->findAll();
    }

    /**
     * @param CreateRequest $createRequest
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return mixed
     *
     * @Rest\Post()
     * @Rest\View()
     *
     * @ParamConverter("createRequest", converter="fos_rest.request_body")
     */
    public function create(CreateRequest $createRequest, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors)) {
            return View::create($validationErrors, Response::HTTP_NOT_ACCEPTABLE);
        }

        $workspace = new Workspace();

        $workspace
            ->setName($createRequest->name)
            ->setDescription($createRequest->description);

        $this->getDocumentManager()->persist($workspace);
        $this->getDocumentManager()->flush();

        return $workspace;
    }
}
