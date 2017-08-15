<?php

namespace {

    use App\Model\User;
    use Prooph\Common\Messaging\Message;
    use Prooph\EventStore\Projection\Projector;

    include "./config.php";

    $projection = $projectionManager->createProjection('test', [Projector::OPTION_PCNTL_DISPATCH => true,]);
    $projection->reset();

    pcntl_signal(SIGQUIT, function () use ($projection) {
        $projection->stop();
    });
    $projection
        ->fromCategory(User::class)
        ->whenAny(
            function (array $state, Message $event) use ($eventBus): array {
                $eventBus->dispatch($event);
                return $state;
            }
        );
    $projection->run(false);
}