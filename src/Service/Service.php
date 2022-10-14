<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class Service
{
    protected ?UserInterface $user;

    public function __construct(protected Security $security,  protected EntityManagerInterface $entityManager)
    {
        $this->user = $security->getUser();
    }
}
