<?php

namespace {

    use App\Model\Command\ChangeEmail;
    use App\Model\Command\RegisterUser;

    include "./config.php";

    $commandBus->dispatch(new RegisterUser([
        'id' => $userId,
        'email' => 'random@email.com',
        'password' => 'test'
    ]));

    for ($i = 0; $i < 5; $i++) {
        $commandBus->dispatch(new ChangeEmail([
            'email' => 'random' . $i . '@email.com',
            'id' => $userId
        ]));
    }
}