<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\CheckOrdersQueue;
use App\Message\FinishOrder;
use App\Repository\OrderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'order:finish-oldest',
    description: 'Finishes the oldest order',
)]
class OrderFinishOldestCommand extends Command
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $order = $this->orderRepository->findOneBy(['finishedAt' => 1], ['placedAt' => 'ASC']);
            if ($order) {
                $this->messageBus->dispatch(new FinishOrder($order->getId()));
            }
            $this->messageBus->dispatch(new CheckOrdersQueue());
            $io->success('Order finished and delivered. Enjoy your pizza!');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('Failed to finish order: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
