<?php

declare(strict_types = 1);

namespace Imper86\DddBundle\Bus;

use Imper86\DDD\Domain\Bus\Event\DomainEvent;
use Imper86\DDD\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus) { }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
