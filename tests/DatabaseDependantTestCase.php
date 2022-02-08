<?php

namespace App\Tests;

use App\Entity\Exchange;
use App\Entity\Payment;
use App\Entity\User;
use App\Entity\UserAccount;
use App\Service\UserAccountService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DatabaseDependantTestCase extends KernelTestCase
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();

    }

    protected function tearDown(): void
    {
        foreach ($this->entityManager->getRepository(Payment::class)->findAll() as $entity) $this->entityManager->remove($entity);
        foreach ($this->entityManager->getRepository(User::class)->findAll() as $entity) $this->entityManager->remove($entity);
        foreach ($this->entityManager->getRepository(UserAccount::class)->findAll() as $entity) $this->entityManager->remove($entity);
        foreach ($this->entityManager->getRepository(Exchange::class)->findAll() as $entity) $this->entityManager->remove($entity);
        $this->entityManager->flush();
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function getNewUser(): User
    {
        $user = new User();
        $user->setEmail(rand(1, 99999) . 'email@test.example');
        $user->setPassword('$argon2id$v=19$m=65536,t=6,p=1$AIC3IESQ64NgHfpVQZqviw$1c7M56xyiaQFBjlUBc7T0s53/PzZCjV56lbHnhOUXx8');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

}
