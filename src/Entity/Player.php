<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private ?string $firstname = null;

    #[ORM\Column(nullable: false)]
    private ?int $number = null;

    #[ORM\Column(length: 180, nullable: false)]
    private ?string $playerFilename = null;
    private ?UploadedFile $playerFile = null;

    #[ORM\Column(nullable: false)]
    private ?string $role = null;

    #[ORM\ManyToOne(targetEntity:"Team", inversedBy:"players")]
    #[ORM\JoinColumn(nullable: false)]
    private $team;


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setFirstName(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getPlayerFilename(): ?string
    {
        return $this->playerFilename;
    }

    public function setPlayerFilename(?string $playerFilename): self
    {
        $this->playerFilename = $playerFilename;
        return $this;
    }

    public function getPlayerFile(): ?UploadedFile
    {
        return $this->playerFile;
    }

    public function setPlayerFile(?UploadedFile $playerFile): self
    {
        $this->playerFile = $playerFile;
        return $this;
    }
}
