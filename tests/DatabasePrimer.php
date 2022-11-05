<?php

namespace App\Tests;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

class DatabasePrimer
{
    public static function prime($kernel): void
    {
        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($entityManager->getMetadataFactory()->getAllMetadata());
    }
}
