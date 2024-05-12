<?php

namespace App\Controller;

use App\Entity\Sport;
use App\Form\Type\SportFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class sportController extends AbstractController
{
    #[Route(path: '/list', name: 'app_sport_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $sports = $entityManager->getRepository(Sport::class)->findAll();
        return $this->render('Pages/Admin/Sport/list.html.twig', [
            'sports' => $sports,
        ]);
    }

    #[Route(path: '/create/{id}', name: 'app_sport_create', defaults: ['id' => null])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sport = new Sport();
        $form = $this
            ->createForm(SportFormType::class, $sport)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sport);
            $entityManager->flush();
        }
        return $this->render('Pages/Admin/Sport/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}