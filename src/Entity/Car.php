<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $brand = null;

    #[ORM\Column(length: 150)]
    private ?string $type_car = null;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, Specificity>
     */
    #[ORM\ManyToMany(targetEntity: Specificity::class, mappedBy: 'car')]
    private Collection $specificities;

    /**
     * @var Collection<int, Trip>
     */
    #[ORM\OneToMany(targetEntity: Trip::class, mappedBy: 'car')]
    private Collection $trips;

    /**
     * @var Collection<int, CarUser>
     */
    #[ORM\OneToMany(targetEntity: CarUser::class, mappedBy: 'car')]
    private Collection $carUsers;



    public function __construct()
    {
        $this->specificities = new ArrayCollection();
        $this->trips = new ArrayCollection();
        $this->carUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getTypeCar(): ?string
    {
        return $this->type_car;
    }

    public function setTypeCar(string $type_car): static
    {
        $this->type_car = $type_car;

        return $this;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Specificity>
     */
    public function getSpecificities(): Collection
    {
        return $this->specificities;
    }

    public function addSpecificity(Specificity $specificity): static
    {
        if (!$this->specificities->contains($specificity)) {
            $this->specificities->add($specificity);
            $specificity->addCar($this);
        }

        return $this;
    }

    public function removeSpecificity(Specificity $specificity): static
    {
        if ($this->specificities->removeElement($specificity)) {
            $specificity->removeCar($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): static
    {
        if (!$this->trips->contains($trip)) {
            $this->trips->add($trip);
            $trip->setCar($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getCar() === $this) {
                $trip->setCar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CarUser>
     */
    public function getCarUsers(): Collection
    {
        return $this->carUsers;
    }

    public function addCarUser(CarUser $carUser): static
    {
        if (!$this->carUsers->contains($carUser)) {
            $this->carUsers->add($carUser);
            $carUser->setCar($this);
        }

        return $this;
    }

    public function removeCarUser(CarUser $carUser): static
    {
        if ($this->carUsers->removeElement($carUser)) {
            // set the owning side to null (unless already changed)
            if ($carUser->getCar() === $this) {
                $carUser->setCar(null);
            }
        }

        return $this;
    }
}
