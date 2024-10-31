<?php

namespace App\Entity;

use App\Repository\CarUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarUserRepository::class)]
class CarUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $active = true;

    // voiture = Relation carUsers => Car
    #[ORM\ManyToOne(inversedBy: 'carUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    // conducteur = Relation car_drivers => User
    #[ORM\ManyToOne(inversedBy: 'car_drivers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }
}
