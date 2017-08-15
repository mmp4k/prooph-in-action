<?php

namespace {

    use App\Model\User;
    use Prooph\Snapshotter\CategorySnapshotProjection;

    include "./config.php";

    $projection = $projectionManager->createReadModelProjection(
        'user_snapshots',
        $snapshotReadModel
    );
    $categoryProjection = new CategorySnapshotProjection($projection, User::class);
    $categoryProjection();
    $projection->run(false);
}