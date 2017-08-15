<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Model\User;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use Prooph\SnapshotStore\SnapshotStore;
use App\Model\UserRepository as BaseUserRepository;

class UserRepository extends AggregateRepository implements BaseUserRepository
{
    public function __construct(EventStore $eventStore, SnapshotStore $snapshotStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(User::class),
            new AggregateTranslator(),
            $snapshotStore,
            null,
            true
        );
    }

    public function save(User $user): void
    {
        $this->saveAggregateRoot($user);
    }

    public function get(string $id): ?User
    {
        return $this->getAggregateRoot($id);
    }
}