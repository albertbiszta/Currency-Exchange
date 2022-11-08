<?php

declare(strict_types=1);

namespace App\Entity;

use App\DoctrineType\CurrencyEnumType;
use App\DoctrineType\PaymentTypeType;
use App\Enum\Currency;
use App\Enum\PaymentType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    private User $user;

    #[ORM\Column(type: CurrencyEnumType::NAME, length: 255)]
    private Currency $currency;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'boolean')]
    private bool $is_completed;

    #[ORM\Column(type: PaymentTypeType::NAME)]
    private PaymentType $type;

    public function __construct(PaymentType $type)
    {
        $this->setType($type);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIsCompleted(): bool
    {
        return $this->is_completed;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->is_completed = $isCompleted;

        return $this;
    }

    public function setMissingAttributes(User $user, bool $isCompleted = false): self
    {
        $this
            ->setDate(new \DateTime)
            ->setIsCompleted($isCompleted)
            ->setUser($user);

        return $this;
    }

    public function getType(): PaymentType
    {
        return $this->type;
    }

    public function getTypeName(): string
    {
        return $this->type->getName();
    }

    public function setType(PaymentType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
