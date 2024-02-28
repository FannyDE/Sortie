<?php

namespace App\DTO;

use DateTime;
use PhpParser\Node\Scalar\String_;

class SearchDTO
{
    private $campus;
    private $search;
    private $startDate;
    private $endDate;
    private $organizer;
    private $registered;
    private $notRegistered;
    private $pastEvent;

    public function __construct(string $campus, string $search, DateTime $startDate, Datetime $endDate, bool $organizer, bool $registered, bool $notRegistered, bool $pastEvent)
    {
        $this->campus = $campus;
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->organizer = $organizer;
        $this->registered = $registered;
        $this->notRegistered = $notRegistered;
        $this->pastEvent = $pastEvent;
    }

    public function getCampus(): string
    {
        return $this->campus;
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function getOrganizer(): bool
    {
        return $this->organizer;
    }

    public function getRegistered(): bool
    {
        return $this->registered;
    }

    public function getNotRegistered(): bool
    {
        return $this->notRegistered;
    }

    public function getPastEvent(): bool
    {
        return $this->pastEvent;
    }
}