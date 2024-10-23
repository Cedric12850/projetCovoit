<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StepRepository::class)]
class Step
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $place_step = null;

    #[ORM\Column]
    private ?int $num_order = null;

    #[ORM\Column]
    private ?float $price_passenger = null;

    #[ORM\Column]
    private ?int $length_km = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    private ?Trip $trip = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'step')]
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Town $town_step = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaceStep(): ?string
    {
        return $this->place_step;
    }

    public function setPlaceStep(?string $place_step): static
    {
        $this->place_step = $place_step;

        return $this;
    }

    public function getNumOrder(): ?int
    {
        return $this->num_order;
    }

    public function setNumOrder(int $num_order): static
    {
        $this->num_order = $num_order;

        return $this;
    }

    public function getPricePassenger(): ?float
    {
        return $this->price_passenger;
    }

    public function setPricePassenger(float $price_passenger): static
    {
        $this->price_passenger = $price_passenger;

        return $this;
    }

    public function getLengthKm(): ?int
    {
        return $this->length_km;
    }

    public function setLengthKm(int $length_km): static
    {
        $this->length_km = $length_km;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): static
    {
        $this->trip = $trip;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setStep($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getStep() === $this) {
                $reservation->setStep(null);
            }
        }

        return $this;
    }

    public function getTownStep(): ?Town
    {
        return $this->town_step;
    }

    public function setTownStep(?Town $town_step): static
    {
        $this->town_step = $town_step;

        return $this;
    }
}
