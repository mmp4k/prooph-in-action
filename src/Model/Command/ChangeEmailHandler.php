<?php

declare(strict_types=1);

namespace App\Model\Command;

use App\Model\UserRepository;

class ChangeEmailHandler
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(ChangeEmail $changeEmail): void
    {
        $user = $this->repository->get($changeEmail->id());
        $user->changeEmail($changeEmail->email());
        $this->repository->save($user);
    }
}