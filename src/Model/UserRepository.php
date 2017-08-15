<?php

declare(strict_types=1);

namespace App\Model;

interface UserRepository
{
    public function save(User $user): void;
    public function get(string $id): ?User;
}