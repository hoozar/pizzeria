<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MenuRepository extends ServiceEntityRepository
{
    use FindByCriteriaTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function save(Menu $menu): void
    {
        $this->getEntityManager()->persist($menu);
        $this->getEntityManager()->flush();
    }
}
