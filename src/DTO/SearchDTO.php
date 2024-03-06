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
    private bool $pastEvents;


    /*public function __construct(
        Campus $campus,
        string $search,
        DateTime $startDate,
        DateTime $endDate,
        bool $organizer,
        bool $registered,
        bool $notRegistered,
        bool $pastEvents
    )
    {
        $this->campus = $campus;
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->organizer = $organizer;
        $this->registered = $registered;
        $this->notRegistered = $notRegistered;
        $this->pastEvents = $pastEvents;
    }*/

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

    public function getPastEvents(): bool
    {
        return $this->pastEvents;
    }

    public function setCampus(Campus $campus): SearchDTO
    {
        $this->campus = $campus;

        return $this;
    }

    public function setSearch(string $search): void
    {
        $this -> search = $search;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this -> startDate = $startDate;
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this -> endDate = $endDate;
    }

    public function setOrganizer(bool $organizer): void
    {
        $this -> organizer = $organizer;
    }

    public function setRegistered(bool $registered): void
    {
        $this -> registered = $registered;
    }

    public function setNotRegistered(bool $notRegistered): void
    {
        $this -> notRegistered = $notRegistered;
    }

    public function setPastEvents(bool $pastEvents): void
    {
        $this -> pastEvents = $pastEvents;
    }


}