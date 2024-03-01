<?php

namespace App\DTO;

use App\Entity\Campus;
use DateTime;
use PhpParser\Node\Scalar\String_;

class SearchDTO
{
    private Campus $campus;
    private string $search;
    private DateTime $startDate;
    private DateTime $endDate;
    private bool $organizer;
    private bool $registered;
    private bool $notRegistered;
    private bool $pastEvent;

    public function __construct($campus, $search, $startDate, $endDate, $organizer, $registered, $notRegistered, $pastEvent)
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

    public function getCampus(): Campus
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

    public function setCampus(Campus $campus): SearchDTO
    {
        $this->campus = $campus;

        return $this;
    }
}