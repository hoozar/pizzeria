<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Order;
use App\Message\FinishOrder;
use App\Repository\OrderRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FinishOrderHandler
{

    public function __construct(
        private readonly OrderRepository $orderRepository,
    ) {
        // empty
    }

    public function __invoke(FinishOrder $message): void
    {
        /** @var Order $order */
        $order = $this->orderRepository->find($message->orderId);
        if ($order) {
            $order->markAsFinished();
            $this->orderRepository->save($order);
        }
    }
}
