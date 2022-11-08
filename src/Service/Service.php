<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class Service
{
    public function __construct(protected EntityManagerInterface $entityManager, protected Security $security)
    {
    }

    protected function getUser(): ?UserInterface
    {
        return $this->security->getUser();
    }

    protected function saveEntity($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
