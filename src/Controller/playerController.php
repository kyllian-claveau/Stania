<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\Type\PlayerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/player')]
class playerController extends AbstractController
{
    #[Route(path: '/list', name: 'app_player_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $players = $entityManager->getRepository(Player::class)->findAll();
        return $this->render('Pages/Admin/Player/list.html.twig', [
            'players' => $players,
        ]);
    }

    #[Route(path: '/create/{id}', name: 'app_player_create', defaults: ['id' => null])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $player = new Player();
        $form = $this
            ->createForm(PlayerFormType::class, $player)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($player->getPlayerFile() !== null) {
                $filename = uniqid() . '.' . $player->getPlayerFile()->guessClientExtension();
                $path = $this->getParameter('kernel.project_dir') . '/public/images/player/' . $filename;
                $content = $player->getPlayerFile()->getContent();
                file_put_contents($path, $content);
                $player->setPlayerFilename($filename);
                $player->setPlayerFile(null);
            }
            $entityManager->persist($player);
            $entityManager->flush();
        }
        return $this->render('Pages/Admin/Player/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}