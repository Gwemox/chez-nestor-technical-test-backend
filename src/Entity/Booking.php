<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue("UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull()
     */
    private $beginAt;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull()
     */
    private $finishAt;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="bookings", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="bookings", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $room;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getFinishAt(): ?\DateTimeInterface
    {
        return $this->finishAt;
    }

    public function setFinishAt(\DateTimeInterface $finishAt): self
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }
}
