<?php

namespace App\Entity;

use App\Repository\PartyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
class Party
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"date")]
    private $date;

    #[ORM\Column(type:"time")]
    private $time;

    #[ORM\Column(type:"integer")]
    private $duration;

    public function __construct()
    {
        // Par défaut, la durée du match est de 60 minutes
        $this->duration = 60;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    #[ORM\Column(type:"decimal", precision: 5, scale: 2)]
    private $teamHomeOdds;

    #[ORM\Column(type:"decimal", precision: 5, scale: 2)]
    private $teamAwayOdds;

    #[ORM\Column(type:"decimal", precision: 5, scale: 2)]
    private $drawOdds;

    #[ORM\Column(nullable: false)]
    private ?string $weather = null;

    #[ORM\ManyToOne(targetEntity:"App\Entity\Team")]
    private $teamHome;

    #[ORM\ManyToOne(targetEntity:"App\Entity\Team")]
    private $teamAway;

    #[ORM\Column(nullable: true)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        // Mettre à jour le statut avant de le retourner
        $this->updateStatus();
        return $this->status;
    }

    public function updateStatus(): void
    {
        $currentDateTime = new \DateTime('now');
        $matchDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->date->format('Y-m-d') . ' ' . $this->time->format('H:i:s'));
        $endTime = (clone $matchDateTime)->modify('+ ' . $this->duration . ' minutes');

        if ($currentDateTime < $matchDateTime) {
            $this->status = 'À venir';
        } elseif ($currentDateTime >= $matchDateTime && $currentDateTime <= $endTime) {
            $this->status = 'En cours';
        } else {
            $this->status = 'Terminé';
        }

        if ($this->homeScore === null && $this->awayScore === null) {
            $this->homeScore = 0;
            $this->awayScore = 0;
        }
    }


    #[ORM\Column(nullable: true)]
    private ?int $homeScore = null;

    #[ORM\Column(nullable: true)]
    private ?int $awayScore = null;

    #[ORM\OneToMany(targetEntity:"App\Entity\Comments", mappedBy:"party", orphanRemoval: "true")]
    private Collection $comments;

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(?int $homeScore): self
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(?int $awayScore): self
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    public function getTeamHomeOdds(): ?float
    {
        return $this->teamHomeOdds;
    }

    public function setTeamHomeOdds(?float $teamHomeOdds): self
    {
        $this->teamHomeOdds = $teamHomeOdds;

        return $this;
    }

    public function getTeamAwayOdds(): ?float
    {
        return $this->teamAwayOdds;
    }

    public function setTeamAwayOdds(?float $teamAwayOdds): self
    {
        $this->teamAwayOdds = $teamAwayOdds;

        return $this;
    }

    public function getDrawOdds(): ?float
    {
        return $this->drawOdds;
    }

    public function setDrawOdds(?float $drawOdds): self
    {
        $this->drawOdds = $drawOdds;

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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTeamHome(): ?Team
    {
        return $this->teamHome;
    }

    public function setTeamHome(?Team $teamHome): self
    {
        $this->teamHome = $teamHome;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(?string $weather): self
    {
        $this->weather = $weather;

        return $this;
    }

    public function getTeamAway(): ?Team
    {
        return $this->teamAway;
    }

    public function setTeamAway(?Team $teamAway): self
    {
        $this->teamAway = $teamAway;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setParty($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getParty() === $this) {
                $comment->setParty(null);
            }
        }

        return $this;
    }
}
