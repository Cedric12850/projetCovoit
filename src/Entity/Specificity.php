<?php

namespace App\Entity;

use App\Repository\SpecificityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecificityRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_SPECI_NAME', fields: ['name'])]
class Specificity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, unique: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = true;

    /**
     * @var Collection<int, Car>
     */
    #[ORM\ManyToMany(targetEntity: Car::class, mappedBy: 'car')]
    private Collection $car;

    public function __construct()
    {
        $this->car = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Car>
     */
    public function getCar(): Collection
    {
        return $this->car;
    }

    public function addCar(Car $car): static
    {
        if (!$this->car->contains($car)) {
            $this->car->add($car);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        $this->car->removeElement($car);

        return $this;
    }
}
