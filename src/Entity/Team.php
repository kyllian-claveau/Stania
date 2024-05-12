<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 180, nullable: false)]
    private ?string $teamFilename = null;
    private ?UploadedFile $teamFile = null;

    #[ORM\ManyToOne(targetEntity:"Sport", inversedBy:"teams")]
    #[ORM\JoinColumn(nullable: false)]
    private $sport;

    #[ORM\OneToMany(targetEntity:"Player", mappedBy:"team")]
    private $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTeamFilename(): ?string
    {
        return $this->teamFilename;
    }

    public function setTeamFilename(?string $teamFilename): self
    {
        $this->teamFilename = $teamFilename;
        return $this;
    }

    public function getTeamFile(): ?UploadedFile
    {
        return $this->teamFile;
    }

    public function setTeamFile(?UploadedFile $teamFile): self
    {
        $this->teamFile = $teamFile;
        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // Définit le côté propriétaire à null
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }

}
