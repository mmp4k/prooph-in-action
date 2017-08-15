<?php

declare(strict_types=1);

namespace App\Model\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;

class ChangeEmail extends Command
{
    use PayloadTrait;

    public function id(): string
    {
        return $this->payload()['id'];
    }

    public function email(): string
    {
        return $this->payload()['email'];
    }
}