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
        foreach ([0, 0, 1] as $isCompletedValue) {
            $this->createPayment($this->getNewUser(), $isCompletedValue);
        }
        $paymentRepository = $this->getPaymentRepository();
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
