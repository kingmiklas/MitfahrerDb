<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[ORM\Entity]
#[ORM\Table(name: 'tUser')]
final class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 11)]
    private int $id;

    #[ORM\Column(name: 'cVorname')]
    private string $firstName;

    #[ORM\Column(name: 'cNachname')]
    private string $lastName;

    #[ORM\Column(name: 'cEmail')]
    private string $email;

    #[ORM\Column(name: 'bIsSchueler')]
    private bool $isStudent;

    #[ORM\Column(name: 'bRaucher')]
    private bool $isSmoker;

    #[ORM\Column(name: 'bTierhaare')]
    private bool $hasAnimals;

    #[ORM\Column(name: 'bMaskenpflicht')]
    private bool $maskRequired;

    #[ORM\Column(name: 'bGeschlecht')]
    private string $gender;

    #[ORM\Column(name: 'cFreiInfo')]
    private string $info;

    #[ORM\Column(name: 'cHeimatsTreffpunkt')]
    private string $meetingPoint;

    #[ORM\OneToMany(mappedBy: 'tUser', targetEntity: PostedRides::class, cascade: ['persist','remove'], fetch: 'EXTRA_LAZY')]
    private PostedRides $postedRides;

    #[ORM\OneToOne(mappedBy: 'tUser', targetEntity: Password::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    private Password $password;

    #[ORM\OneToMany(mappedBy: 'tUser',targetEntity: UserRides::class,cascade: ['persist','remove'], fetch: 'EXTRA_LAZY')]
    private UserRides $userRides;

    public function __construct(
        string      $firstName,
        string      $lastName,
        string      $email,
        bool        $isStudent,
        bool        $isSmoker,
        bool        $hasAnimals,
        bool        $maskRequired,
        string      $gender,
        string      $info,
        string      $meetingPoint,
        PostedRides $postedRides,
        Password    $password,
        UserRides   $userRides
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->isStudent = $isStudent;
        $this->isSmoker = $isSmoker;
        $this->hasAnimals = $hasAnimals;
        $this->maskRequired = $maskRequired;
        $this->gender = $gender;
        $this->info = $info;
        $this->meetingPoint = $meetingPoint;
        $this->postedRides = $postedRides;
        $this->password = $password;
        $this->userRides = $userRides;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function isStudent(): bool
    {
        return $this->isStudent;
    }

    public function setIsStudent(bool $isStudent): void
    {
        $this->isStudent = $isStudent;
    }

    public function isSmoker(): bool
    {
        return $this->isSmoker;
    }

    public function setIsSmoker(bool $isSmoker): void
    {
        $this->isSmoker = $isSmoker;
    }

    public function isHasAnimals(): bool
    {
        return $this->hasAnimals;
    }

    public function setHasAnimals(bool $hasAnimals): void
    {
        $this->hasAnimals = $hasAnimals;
    }

    public function isMaskRequired(): bool
    {
        return $this->maskRequired;
    }

    public function setMaskRequired(bool $maskRequired): void
    {
        $this->maskRequired = $maskRequired;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getInfo(): string
    {
        return $this->info;
    }

    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    public function getMeetingPoint(): string
    {
        return $this->meetingPoint;
    }

    public function setMeetingPoint(string $meetingPoint): void
    {
        $this->meetingPoint = $meetingPoint;
    }

    public function getPostedRides(): PostedRides
    {
        return $this->postedRides;
    }

    public function setPostedRides(PostedRides $postedRides): void
    {
        $this->postedRides = $postedRides;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function setPassword(Password $password): void
    {
        $this->password = $password;
    }

    public function getUserRides(): UserRides
    {
        return $this->userRides;
    }

    public function setUserRides(UserRides $userRides): void
    {
        $this->userRides = $userRides;
    }

}
