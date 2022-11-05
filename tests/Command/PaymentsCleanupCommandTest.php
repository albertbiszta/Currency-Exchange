<?php

namespace App\Tests\Command;

use App\Entity\Payment;
use App\Service\CurrencyService;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PaymentsCleanupCommandTest extends DatabaseDependantTestCase
{
    /** @test */
    public function the_command_deletes_incomplete_payments()
    {
        foreach ([false, false, true] as $isCompletedValue) {
            $this->createPayment($this->createUser(), 1000, CurrencyService::EURO_SHORTNAME, $isCompletedValue);
        }
        $paymentRepository = $this->getRepository(Payment::class);
        $incompletePayments = $paymentRepository->findBy(['is_completed' => 0]);
        $this->assertCount(2, $incompletePayments);
        $this->assertCount(3, $paymentRepository->findAll());

        $command = (new Application(self::$kernel))->find('app:delete-incomplete-payments');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertCount(0, $paymentRepository->findBy(['is_completed' => 0]));
        $this->assertCount(1, $paymentRepository->findAll());
    }
}
