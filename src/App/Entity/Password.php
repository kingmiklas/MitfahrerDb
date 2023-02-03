<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'tPasswort')]
final class Password
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 11)]
    private int $id;

    #[ORM\Column(name: 'cPassword')]
    private string $password;

    #[ORM\OneToOne(inversedBy: 'password', targetEntity: User::class, cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'kUser', onDelete: 'cascade')]
    private User $user;

    public function __construct(string $password, User $user)
    {
        $this->password = $password;
        $this->user = $user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
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
