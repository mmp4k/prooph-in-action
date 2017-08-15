<?php

declare(strict_types=1);

namespace App\Model\Event;

use Prooph\EventSourcing\AggregateChanged;

class EmailChanged extends AggregateChanged
{
    public function email(): string
    {
        return $this->payload['email'];
    }
}