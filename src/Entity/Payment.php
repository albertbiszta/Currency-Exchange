<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    public const TYPE_DEPOSIT = 0;
    public const TYPE_WITHDRAW = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    private User $user;

    #[ORM\Column(type: 'string', length: 255)]
    private string $currency;

    #[ORM\Column(type: 'float')]
    private float $amount;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'boolean')]
    private bool $is_completed;

    #[ORM\Column(type: 'smallint')]
    private ?int $type;

    public function __construct(int $type)
    {
        $this->setType($type);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIsCompleted(): ?bool
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getTypeName(): string
    {
        return $this->type === self::TYPE_DEPOSIT ? 'deposit': 'withdraw';
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
