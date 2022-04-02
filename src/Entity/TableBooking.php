<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TableBookingRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TableBookingRepository::class)]
class TableBooking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tableBookings')]
    private $booker;

    #[ORM\Column(type: 'datetime')]
    private $date;

    #[Assert\NotNull()]
    #[ORM\Column(type: 'string', length: 255)]
    private $timesheet;

    #[Assert\Range(min:1,max:4)]
    #[ORM\Column(type: 'integer')]
    private $guests;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTimesheet(): ?string
    {
        return $this->timesheet;
    }

    public function setTimesheet(string $timesheet): self
    {
        $this->timesheet = $timesheet;

        return $this;
    }

    public function getGuests(): ?int
    {
        return $this->guests;
    }

    public function setGuests(int $guests): self
    {
        $this->guests = $guests;

        return $this;
    }


    
}
