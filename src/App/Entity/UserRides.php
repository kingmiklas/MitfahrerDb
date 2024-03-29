<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'tUserRides')]
final class UserRides
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 11)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: PostedRides::class, cascade: ['persist'], inversedBy: 'id')]
    #[ORM\JoinColumn(name: 'kRide', onDelete: 'cascade')]
    private PostedRides $ride;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'id')]
    #[ORM\JoinColumn(name: 'kUser', onDelete: 'cascade')]
    private User $user;

    public function __construct(PostedRides $postedRides, User $user)
    {
        $this->postedRides = $postedRides;
        $this->user = $user;
    }

    public function getPostedRides(): PostedRides
    {
        return $this->postedRides;
    }

    public function setPostedRides(PostedRides $postedRides): void
    {
        $this->postedRides = $postedRides;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}
