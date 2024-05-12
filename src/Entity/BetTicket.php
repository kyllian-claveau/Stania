<?php

namespace App\Entity;

use App\Repository\BetTicketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: BetTicketRepository::class)]
class BetTicket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "bets")]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bet", mappedBy="ticket")
     */
    #[ORM\OneToMany(targetEntity: Bet::class, mappedBy: "ticket")]
    private $bets;


    #[ORM\Column(type: "float")]
    private ?float $amountBet = null;

    #[ORM\Column(type: "float")]
    private ?float $potentialWin = null;

    #[ORM\Column(type: "float")]
    private ?float $totalOdds = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    /**
     * @return Collection|Bet[]
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function getAmountBet(): ?float
    {
        return $this->amountBet;
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

    public function setAmountBet(float $amountBet): self
    {
        $this->amountBet = $amountBet;

        return $this;
    }

    public function getPotentialWin(): ?float
    {
        return $this->potentialWin;
    }

    public function setPotentialWin(float $potentialWin): self
    {
        $this->potentialWin = $potentialWin;

        return $this;
    }

    public function getTotalOdds(): ?float
    {
        return $this->totalOdds;
    }

    public function setTotalOdds(float $totalOdds): self
    {
        $this->totalOdds = $totalOdds;

        return $this;
    }
}