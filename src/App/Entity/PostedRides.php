<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Db\Sql\Ddl\Column\Datetime;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'tPostedRides')]
final class PostedRides
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 11)]
    private int $id;

    #[ORM\Column(name: 'dDatumUhrzeit')]
    private Datetime $datetime;

    #[ORM\Column(name: 'cStartOrt')]
    private string $start;

    #[ORM\Column(name: 'cZielOrt')]
    private string $end;

    #[ORM\Column(name: 'nSitzplaetze')]
    private int $seats;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy:'postedRides')]
    #[ORM\JoinColumn(name: 'kErsteller', onDelete: 'cascade')]
    private User $creater;

    #[ORM\Column(name: 'nPreis')]
    private int $price;

    #[ORM\Column(name: 'bIsStorniert')]
    private bool $canceled;

    public function __construct(
        Datetime $datetime,
        string   $start,
        string   $end,
        int      $seats,
        User     $creater,
        int      $price,
        bool     $canceled
    ) {
        $this->datetime = $datetime;
        $this->start = $start;
        $this->end = $end;
        $this->seats = $seats;
        $this->creater = $creater;
        $this->price = $price;
        $this->canceled = $canceled;
    }

    public function getDatetime(): Datetime
    {
        return $this->datetime;
    }

    public function setDatetime(Datetime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    public function getEnd(): string
    {
        return $this->end;
    }

    public function setEnd(string $end): void
    {
        $this->end = $end;
    }

    public function getSeats(): int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): void
    {
        $this->seats = $seats;
    }

    public function getCreater(): User
    {
        return $this->creater;
    }

    public function setCreater(User $creater): void
    {
        $this->creater = $creater;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    public function setCanceled(bool $canceled): void
    {
        $this->canceled = $canceled;
    }
}
