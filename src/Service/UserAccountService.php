<?php

namespace App\Service;

use App\Repository\UserAccountRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserAccountService
{
    private $user;

    public function __construct(private UserAccountRepository $userAccountRepository, Security $security,)
    {
        $this->user = $security->getUser();
    }

    public function isAccountBalanceSufficient(string $primaryCurrency, float $exchangeAmount): bool
    {
      // return $this->userAccountRepository->findOneBy(['user' => $this->user, 'currency' => $primaryCurrency])->getAmount() >= $exchangeAmount;
    }

}
