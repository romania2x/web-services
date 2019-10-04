<?php

namespace App\Security;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @var DocumentRepository
     */
    private $repository;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
        $this->repository = $documentManager->getRepository(User::class);
    }

    public function loadUserByUsername($username)
    {
        return $this->repository->findOneBy(['email' => $username]);
    }

    public function refreshUser(UserInterface $user)
    {
        $this->documentManager->refresh($user);
    }

    public function supportsClass($class)
    {
        return User::class == $class;
    }
}
