<?php

namespace App\Controller;

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
        $sports = $entityManager->getRepository(Sport::class)->findAll();
        return $this->render('index.html.twig', [
            'partys' => $partys,
            'sports' => $sports
        ]);
    }
}