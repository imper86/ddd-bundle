<?php

declare(strict_types = 1);

namespace Imper86\DddBundle\Bus;

use Imper86\DDD\Domain\Bus\Command\Command;
use Imper86\DDD\Domain\Bus\Command\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus) { }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
