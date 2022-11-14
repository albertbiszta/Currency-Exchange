<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Entity\Payment;
use App\Entity\User;
use App\Enum\Currency;
use App\Enum\PaymentType;
use App\Repository\PaymentRepository;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class PaymentsCleanupCommandTest extends DatabaseDependantTestCase
{
    private CommandTester $commandTester;
    private PaymentRepository $paymentRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $command = (new Application(self::$kernel))->find('app:delete-incomplete-payments');
        $this->commandTester = new CommandTester($command);
        $this->paymentRepository = $this->getRepository(Payment::class);
    }

    public function testRunningCommandShouldDeleteIncompletePayments()
    {
        foreach ([false, false, true] as $isCompletedValue) {
            $this->createPayment($this->createUser(), $isCompletedValue);
        }
        $this->assertCount(2, $this->paymentRepository->findBy(['is_completed' => false]));
        $this->executeCommand();
        $this->assertCount(1, $this->paymentRepository->findAll());
    }

    public function testRunningCommandShouldDisplayNumberOfDeletedPayments()
    {
        $this->executeCommand();
        $this->assertStringContainsString('Deleted 0 payments', $this->commandTester->getDisplay());

        $this->createPayment($this->createUser());
        $this->executeCommand();
        $this->assertStringContainsString('Deleted 1 payment', $this->commandTester->getDisplay());

        $this->createPayment($this->createUser());
        $this->createPayment($this->createUser());
        $this->executeCommand();
        $this->assertStringContainsString('Deleted 2 payments', $this->commandTester->getDisplay());
    }

    private function createPayment(User $user, bool $isCompleted = false): void
    {
        $payment = new Payment(PaymentType::DEPOSIT);
        $payment
            ->setUser($user)
            ->setAmount(1000)
            ->setCurrency(Currency::EURO)
            ->setIsCompleted($isCompleted)
            ->setDate(new \DateTime);
        $this->saveEntity($payment);
    }

    private function executeCommand(): void
    {
        $this->commandTester->execute([]);
    }
}
