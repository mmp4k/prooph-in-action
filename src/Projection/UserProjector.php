<?php

declare(strict_types=1);

namespace App\Projection;

use App\Model\Event\EmailChanged;
use App\Model\Event\UserRegistered;

class UserProjector
{
    private $PDO;

    public function __construct(\PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function onUserRegistered(UserRegistered $userRegistered): void
    {
        $query = $this->PDO->prepare('INSERT INTO `read_users` SET email = ?, password = ?, id = ?');
        $query->bindValue(1, $userRegistered->email());
        $query->bindValue(2, $userRegistered->password());
        $query->bindValue(3, $userRegistered->aggregateId());
        $query->execute();

    }

    public function onEmailChanged(EmailChanged $emailChanged): void
    {
        $query = $this->PDO->prepare('UPDATE `read_users` SET email = ? WHERE id = ?');
        $query->bindValue(1, $emailChanged->email());
        $query->bindValue(2, $emailChanged->aggregateId());
        $query->execute();
    }
}