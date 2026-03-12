<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Validator\Constraints as Assert;

class OrderDTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    private string $address;

    #[Assert\NotBlank]
    private Menu $menu;

    #[Assert\NotBlank]
    private int $quantity;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu): void
    {
        $this->menu = $menu;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
