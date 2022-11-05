<?php

namespace App\Entity;

use App\Repository\ExchangeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
class Exchange
{
    public const PRIMARY_CURRENCY = 'primaryCurrency';
    public const TARGET_CURRENCY = 'targetCurrency';
    public const AMOUNT = 'amount';
    public const AMOUNT_AFTER_EXCHANGE = 'amountAfterExchange';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 3)]
    private string $primaryCurrency;

    #[ORM\Column(type: 'string', length: 3)]
    private string $targetCurrency;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'float')]
    private float $amountAfterExchange;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'exchanges')]
    #[ORM\JoinColumn(nullable: false)]
    private User $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrimaryCurrency(): ?string
    {
        return $this->primaryCurrency;
    }

    public function setPrimaryCurrency(string $primaryCurrency): self
    {
        $this->primaryCurrency = $primaryCurrency;

        return $this;
    }

    public function getTargetCurrency(): ?string
    {
        return $this->targetCurrency;
    }

    public function setTargetCurrency(string $targetCurrency): self
    {
        $this->targetCurrency = $targetCurrency;

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

    public function getAmountAfterExchange(): ?float
    {
        return $this->amountAfterExchange;
    }

    public function setAmountAfterExchange(float $amountAfterExchange): self
    {
        $this->amountAfterExchange = $amountAfterExchange;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function setMissingAttributes(User $user, float $amountAfterExchange): self
    {
        $this
            ->setAmountAfterExchange($amountAfterExchange)
            ->setDate(new \DateTime)
            ->setUser($user);

        return $this;
    }
}
