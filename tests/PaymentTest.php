<?php

namespace App\Tests;

use App\Entity\Payment;
use App\Entity\User;

class PaymentTest extends DatabaseDependantTestCase
{
    /** @test */
        public function payment_can_be_added_to_the_database()
      {
          $paymentRepository = $this->getPaymentRepository();
          $this->createPayment($this->getNewUser(), 1);
          $this->assertCount(1, $paymentRepository->findAll());
          $this->assertEquals('1111', $paymentRepository->findOneBy(['id' => 1, 'currency' => 'eur'])->getAmount());
      }

      protected function createPayment(User $user, int $isCompleted): void
      {
          $payment = new Payment();
          $payment
              ->setCurrency('eur')
              ->setAmount(1111)
              ->setDate(new \DateTime())
              ->setIsCompleted($isCompleted)
              ->setUser($user);
          $this->entityManager->persist($payment);
          $this->entityManager->flush();
      }

      protected function getPaymentRepository(): \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
      {
          return $this->entityManager->getRepository(Payment::class);
      }
}
