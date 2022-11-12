<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserAccount;
use App\Enum\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method UserAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccount[]    findAll()
 * @method UserAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccount::class);
    }

    public function findOneByUserAndCurrency(UserInterface $user, Currency $currency): ?UserAccount
    {
        return $this->findOneBy(['User' => $user, 'currency' => ['value' => $currency->getCode()]]);
    }
}
