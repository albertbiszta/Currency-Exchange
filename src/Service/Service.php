<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class Service
{
    public function __construct(protected Security $security, protected EntityManagerInterface $entityManager)
    {
    }

    protected function getUser(): ?UserInterface
    {
        return $this->security->getUser();
    }
}
