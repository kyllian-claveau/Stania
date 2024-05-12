<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\Type\TeamFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/team')]
class teamController extends AbstractController
{
    #[Route(path: '/list', name: 'app_team_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $teams = $entityManager->getRepository(Team::class)->findAll();
        return $this->render('Pages/Admin/Team/list.html.twig', [
            'teams' => $teams,
        ]);
    }

    #[Route(path: '/create/{id}', name: 'app_team_create', defaults: ['id' => null])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Team();
        $form = $this
            ->createForm(TeamFormType::class, $team)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($team->getTeamFile() !== null) {
                $filename = uniqid() . '.' . $team->getTeamFile()->guessClientExtension();
                $path = $this->getParameter('kernel.project_dir') . '/public/images/logo/' . $filename;
                $content = $team->getTeamFile()->getContent();
                file_put_contents($path, $content);
                $team->setTeamFilename($filename);
                $team->setTeamFile(null);
            }
            $entityManager->persist($team);
            $entityManager->flush();
        }
        return $this->render('Pages/Admin/Team/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}