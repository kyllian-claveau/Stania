<?php

namespace App\Entity;

use App\Repository\BetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BetRepository::class)]
class Bet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string")]
    private ?string $team = null;

    #[ORM\Column(type: "float")]
    private ?float $odds = null;

    #[ORM\ManyToOne(targetEntity: BetTicket::class, inversedBy: "bets")]
    #[ORM\JoinColumn(nullable: false)]
    private $ticket;

    #[ORM\Column(type: "integer")]
    private ?int $matchId = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "bets")]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: "string")]
    private ?string $matchName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicket(): ?BetTicket
    {
        return $this->ticket;
    }

    public function setTicket(?BetTicket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }
    public function setTeam(string $team): self
    {
        $this->team = $team;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOdds(): ?float
    {
        return $this->odds;
    }

    public function setOdds(float $odds): self
    {
        $this->odds = $odds;
        return $this;
    }

    public function getMatchId(): ?int
    {
        return $this->matchId;
    }

    public function setMatchId(int $matchId): self
    {
        $this->matchId = $matchId;
        return $this;
    }

    public function getMatchName(): ?string
    {
        return $this->matchName;
    }

    public function setMatchName(string $matchName): self
    {
        $this->matchName = $matchName;
        return $this;
    }
}

