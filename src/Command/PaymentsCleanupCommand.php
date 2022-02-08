<?php

namespace App\Command;

use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentsCleanupCommand extends Command
{
    protected static $defaultName = 'app:delete-incomplete-payments';

    public function __construct(private PaymentRepository $paymentRepository, private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Delete incomplete payments');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $numberOfDeletedPayments = 0;
        foreach ($this->paymentRepository->findBy(['is_completed' => 0]) as $payment) {
            $this->entityManager->remove($payment);
            $numberOfDeletedPayments++;
        }
        $this->entityManager->flush();
        $output->write("Deleted {$numberOfDeletedPayments} payments");

        return Command::SUCCESS;
    }
}
