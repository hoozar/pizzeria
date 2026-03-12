<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    use FindByCriteriaTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $order): void
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    public function getQueueSummary(): array
    {
        $orders = $this->findByCriteria(['finishedAt' => 1]);
        $count = count($orders);
        $quantity = 0;
        $waitTime = 0;

        foreach ($orders as $order) {
            $quantity += $order->getQuantity();
            $waitTime += $order->getQuantity() * 10; // arbitralnie, ale można dać do Menu i per rodzaj pizzy
        }

        return [
            'count' => $count,
            'quantity' => $quantity,
            'waitTime' => $waitTime,
        ];
    }
}
