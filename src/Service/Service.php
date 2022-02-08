<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

abstract class Service
{
    protected ?\Symfony\Component\Security\Core\User\UserInterface $user;

    public function __construct(Security $security,  protected EntityManagerInterface $entityManager)
    {
        $this->user = $security->getUser();
    }
}
