<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 31)]
    private string $name = '';

    #[ORM\Column]
    private ?string $description = null;

    #[ORM\Column]
    private float $price = 0.0;

    #[ORM\Column]
    private ?string $picture = null;

    public function __construct(
        string $name = '',
        ?string $description = null,
        float $price = 0.0,
        ?string $picture = null,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->picture = $picture;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'picture' => $this->picture,
        ];
    }
}
