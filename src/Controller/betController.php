<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\BetTicket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class betController extends AbstractController
{
    #[Route('/save-bet', name: 'app_save_bet', methods: ['POST'])]
    public function saveBet(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!$security->getUser()) {
            return $this->json(['error' => 'Vous devez être connecté pour placer un pari.'], 403);
        }

        $betSelections = json_decode($request->request->get('betSelections'), true);
        $betAmount = floatval($request->request->get('betAmount')); // Récupérer le montant total misé par l'utilisateur
        $potentialWin = floatval($request->request->get('potentialWin')); // Récupérer le gain potentiel
        $totalOdds = 1.0;
        foreach ($betSelections as $selection) {
            $totalOdds *= floatval($selection['odds']);
        }

        // Créer un nouveau ticket avec l'utilisateur actuel et les valeurs récupérées
        $ticket = new BetTicket($security->getUser());
        $ticket->setAmountBet($betAmount);
        $ticket->setPotentialWin($potentialWin);
        $ticket->setTotalOdds($totalOdds);
        $ticket->setUser($security->getUser());
        $entityManager->persist($ticket);

        // Enregistrer chaque pari et les associer au ticket
        foreach ($betSelections as $selection) {
            $bet = new Bet();
            $bet->setTeam($selection['team']);
            $bet->setOdds($selection['odds']);
            $bet->setMatchId($selection['matchId']);
            $bet->setMatchName($selection['matchName']);
            $bet->setUser($security->getUser());
            $bet->setTicket($ticket);
            $entityManager->persist($bet);
        }

        $entityManager->flush();
        return $this->json(['success' => true]);
    }

}
