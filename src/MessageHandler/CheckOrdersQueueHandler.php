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
    private const int TO_LONG_WAITING_TRESHOLD = 60;

    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly LoggerInterface $logger,
    ) {
        // empty
    }

    public function __invoke(CheckOrdersQueue $message): void
    {
        $stats = $this->orderRepository->getQueueSummary();
        if ($stats['waitTime'] > self::TO_LONG_WAITING_TRESHOLD) {
            $this->logger->warning('Queue is waiting for too long: ' . $stats['waitTime'] . ' seconds');
        }
    }
}
