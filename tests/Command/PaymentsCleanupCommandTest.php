<?php

namespace App\Tests\Command;

use App\Tests\PaymentTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PaymentsCleanupCommandTest extends PaymentTest
{
    /** @test */
    public function the_command_deletes_incomplete_payments()
    {
        $testUser = $this->getNewUser();
        $this->createPayment($testUser, 0);
        $this->createPayment($testUser, 0);
        $this->createPayment($testUser, 0);
        $this->createPayment($testUser, 1);

        $paymentRepository = $this->getPaymentRepository();
        $incompletePayments = $paymentRepository->findBy(['is_completed' => 0]);
        $this->assertCount(3, $incompletePayments);
        $this->assertCount(4, $paymentRepository->findAll());

        $command = (new Application(self::$kernel))->find('app:delete-incomplete-payments');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertCount(0, $paymentRepository->findBy(['is_completed' => 0]));
        $this->assertCount(1, $paymentRepository->findAll());
    }
}
