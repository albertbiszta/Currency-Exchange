<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PaymentsCleanupCommand extends Command
{
    protected static $defaultName = 'app:delete-incomplete-payments';

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly PaymentRepository $paymentRepository)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Delete incomplete payments');
    }

    /**
     * run after midnight
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $payments = $this->paymentRepository->findIncomplete();
        $numberOfPayments = count($payments);
        foreach ($payments as $payment) {
            $this->entityManager->remove($payment);
        }
        $this->entityManager->flush();
        $output->write($this->getSuccessMessage($numberOfPayments) . PHP_EOL);

        return Command::SUCCESS;
    }

    private function getSuccessMessage(int $numberOfPayments): string
    {
        return "Deleted $numberOfPayments " . ($numberOfPayments === 1 ? 'payment' : 'payments');
    }
}
