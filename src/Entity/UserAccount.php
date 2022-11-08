<?php

declare(strict_types=1);

namespace App\Entity;

use App\DoctrineType\CurrencyEnumType;
use App\Enum\Currency;
use App\Repository\UserAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAccountRepository::class)]
class UserAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userAccounts')]
    #[ORM\JoinColumn(nullable: false)]
    private User $User;

    #[ORM\Column(type: CurrencyEnumType::NAME, length: 3)]
    private Currency $currency;

    #[ORM\Column(type: 'float')]
    private float $amount;

    public function __construct(User $user, float $amount, Currency $currency)
    {
        $this
            ->setUser($user)
            ->setAmount($amount)
            ->setCurrency($currency);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
