<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\CheckOrdersQueue;
use App\Repository\OrderRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CheckOrdersQueueHandler
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly LoggerInterface $logger,
        private readonly int $queueWaitingThreshold,
    ) {
        // empty
    }

    public function __invoke(CheckOrdersQueue $message): void
    {
        $stats = $this->orderRepository->getQueueSummary();
        if ($stats['waitTime'] > $this->queueWaitingThreshold) {
            $this->logger->warning('Queue is waiting for too long: ' . $stats['waitTime'] . ' seconds');
        }
    }
}
