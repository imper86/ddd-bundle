<?php

declare(strict_types = 1);

namespace Imper86\DddBundle\Bus;

use Imper86\DDD\Domain\Bus\Exception\NoResponseException;
use Imper86\DDD\Domain\Bus\Query\Query;
use Imper86\DDD\Domain\Bus\Query\QueryBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $queryBus) { }

    public function ask(Query $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);

        $handledStamp = $envelope->last(HandledStamp::class);

        if ($handledStamp instanceof HandledStamp) {
            return $handledStamp->getResult();
        }

        throw new NoResponseException();
    }
}
