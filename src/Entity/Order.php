<?php

declare(strict_types=1);

namespace App\Entity;

use App\Form\OrderDTO;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table('orderr')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 127)]
    private string $email = '';

    #[ORM\Column(length: 255)]
    private string $name = '';

    #[ORM\Column(length: 511)]
    private string $address = '';

    #[ORM\ManyToOne(targetEntity: Menu::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menu $menu;

    #[ORM\Column]
    private int $quantity = 0;

    #[ORM\Column]
    private int $placedAt = 0;

    #[ORM\Column]
    private int $finishedAt = 1;

    public function __construct(
        string $email,
        string $name,
        string $address,
        ?Menu $menu,
        int $quantity
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->address = $address;
        $this->menu = $menu;
        $this->quantity = $quantity;
        $this->placedAt = time();
        $this->finishedAt = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPlacedAt(): int
    {
        return $this->placedAt;
    }

    public function getFinishedAt(): int
    {
        return $this->finishedAt;
    }

    public function markAsFinished(): void
    {
        if ($this->finishedAt === 1) {
            $this->finishedAt = time();
        } else {
            throw new \RuntimeException('Order already finished');
        }
    }

    public static function createFromDTO(OrderDTO $dto): self
    {
        return new self(
            $dto->getEmail(),
            $dto->getName(),
            $dto->getAddress(),
            $dto->getMenu(),
            $dto->getQuantity()
        );
    }
}
