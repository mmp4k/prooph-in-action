<?php

namespace {

    use App\Model\User;
    use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
    use Prooph\Snapshotter\CategorySnapshotProjection;
    use Prooph\Snapshotter\SnapshotReadModel;

    include "./config.php";

    $snapshotReadModel = new SnapshotReadModel(
        $userRepository,
        new AggregateTranslator(),
        $pdoSnapshotStore,
        [User::class]
    );

    $projection = $projectionManager->createReadModelProjection(
        'user_snapshots',
        $snapshotReadModel
    );
    $categoryProjection = new CategorySnapshotProjection($projection, User::class);
    $categoryProjection();
    $projection->run(false);
}