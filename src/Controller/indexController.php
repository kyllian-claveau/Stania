<?php

namespace App\Controller;

use App\Entity\Bet;
use App\Entity\Party;
use App\Entity\Sport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class indexController extends AbstractController
{
    #[Route(path: '/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $partys = $entityManager->getRepository(Party::class)->findAll();
        $filteredParties = array_filter($partys, function($party) {
            $party->updateStatus();
            return in_array($party->getStatus(), ['À venir', 'En cours']);
        });

        $sports = $entityManager->getRepository(Sport::class)->findAll();
        $bets = $entityManager->getRepository(Bet::class)->findAll();
        return $this->render('Pages/Basic/Index/index.html.twig', [
            'partys' => $filteredParties,
            'sports' => $sports,
            'bets' => $bets,
        ]);
    }
}