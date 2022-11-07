<?php

namespace App\Tests;

use App\Entity\Exchange;
use App\Entity\Payment;
use App\Entity\User;
use App\Entity\UserAccount;
use App\Enum\Currency;
use App\Enum\PaymentType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DatabaseDependantTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        DatabasePrimer::prime($this->client->getKernel());
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        foreach ([Exchange::class, Payment::class, User::class, UserAccount::class] as $className) {
            foreach ($this->entityManager->getRepository($className)->findAll() as $entity) {
                $this->entityManager->remove($entity);
            }
        }
        $this->entityManager->flush();
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function getRepository(string $entityName): EntityRepository
    {
        return $this->entityManager->getRepository($entityName);
    }

    protected function createUser(): User
    {
        $user = new User();
        $user->setEmail(rand(1, 99999) . 'email@test.example');
        $user->setPassword('$argon2id$v=19$m=65536,t=6,p=1$AIC3IESQ64NgHfpVQZqviw$1c7M56xyiaQFBjlUBc7T0s53/PzZCjV56lbHnhOUXx8');
        $this->saveEntity($user);

        return $user;
    }

    protected function createUserAccount(User $user, float $amount, Currency $currency): UserAccount
    {
        $userAccount = new UserAccount($user, $amount, $currency);
        $this->saveEntity($userAccount);

        return $userAccount;
    }

    protected function createPayment(User $user, float $amount, Currency $currency, bool $isCompleted = false, PaymentType $type = PaymentType::DEPOSIT): Payment
    {
        $payment = new Payment($type);
        $payment->setUser($user)
            ->setAmount($amount)
            ->setCurrency($currency)
            ->setIsCompleted($isCompleted)
            ->setDate(new \DateTime);
        $this->saveEntity($payment);

        return $payment;
    }

    protected function getLoggedUser(): User
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        return $user;
    }

    private function saveEntity($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
