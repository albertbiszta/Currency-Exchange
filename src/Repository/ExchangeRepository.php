<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Exchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exchange|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exchange|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exchange[]    findAll()
 * @method Exchange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exchange::class);
    }
}
