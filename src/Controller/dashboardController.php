<?php

namespace App\Controller;

use App\Entity\BetTicket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route(path:'/admin')]
class dashboardController extends AbstractController
{
    #[Route(path:'/dashboard', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('Pages/Admin/Dashboard/dashboard.html.twig');
    }

    #[Route(path:'/my-bet', name: 'app_admin_bet')]
    public function myBet(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        $tickets = $entityManager->getRepository(BetTicket::class)->findBy(['user' => $user]);
        return $this->render('Pages/Admin/Bet/my-bet.html.twig',[
            'tickets' => $tickets
        ]);
    }
}