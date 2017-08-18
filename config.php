<?php

namespace {

    use App\Model\Command\ChangeEmail;
    use App\Model\Command\ChangeEmailHandler;
    use App\Model\Command\RegisterUser;
    use App\Model\Command\RegisterUserHandler;
    use App\Model\Event\EmailChanged;
    use App\Model\Event\UserRegistered;
    use App\Infrastructure\UserRepository;
    use App\Projection\UserProjector;
    use Prooph\Common\Event\ProophActionEventEmitter;
    use Prooph\Common\Messaging\FQCNMessageFactory;
    use Prooph\EventStore\ActionEventEmitterEventStore;
    use Prooph\EventStore\Pdo\MySqlEventStore;
    use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
    use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
    use Prooph\EventStoreBusBridge\EventPublisher;
    use Prooph\ServiceBus\CommandBus;
    use Prooph\ServiceBus\EventBus;
    use Prooph\ServiceBus\Plugin\Router\CommandRouter;
    use Prooph\ServiceBus\Plugin\Router\EventRouter;
    use Prooph\SnapshotStore\Pdo\PdoSnapshotStore;

    include "./vendor/autoload.php";

    $pdo = new PDO('mysql:dbname=prooph;host=localhost', 'root', '');
    $eventStore = new MySqlEventStore(new FQCNMessageFactory(), $pdo, new MySqlAggregateStreamStrategy());
    $eventEmitter = new ProophActionEventEmitter();
    $eventStore = new ActionEventEmitterEventStore($eventStore, $eventEmitter);

    $eventBus = new EventBus($eventEmitter);
    $eventPublisher = new EventPublisher($eventBus);
    $eventPublisher->attachToEventStore($eventStore);

    $pdoSnapshotStore = new PdoSnapshotStore($pdo);
    $userRepository = new UserRepository($eventStore, $pdoSnapshotStore);

    $projectionManager = new MySqlProjectionManager($eventStore, $pdo);

    $commandBus = new CommandBus();
    $router = new CommandRouter();
    $router->route(RegisterUser::class)->to(new RegisterUserHandler($userRepository));
    $router->route(ChangeEmail::class)->to(new ChangeEmailHandler($userRepository));
    $router->attachToMessageBus($commandBus);

    $userProjector = new UserProjector($pdo);
    $eventRouter = new EventRouter();
    $eventRouter->route(EmailChanged::class)->to([$userProjector, 'onEmailChanged']);
    $eventRouter->route(UserRegistered::class)->to([$userProjector, 'onUserRegistered']);
    $eventRouter->attachToMessageBus($eventBus);

    $userId = '20';
}