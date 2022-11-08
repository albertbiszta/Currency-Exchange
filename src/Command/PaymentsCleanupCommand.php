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
        $output->write("Deleted $numberOfPayments payments \n");

        return Command::SUCCESS;
    }
}
