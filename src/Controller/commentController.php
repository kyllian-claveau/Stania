<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Party;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class commentController extends AbstractController
{
    #[Route(path: '/comment/new/{id}', name: 'app_new_comment', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Party $party): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Seuls les administrateurs peuvent créer des commentaires.');
        }

        $comment = new Comments();
        $comment->setParty($party);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_show_party', ['id' => $party->getId()]);
        }

        // Si le formulaire n'est pas valide ou s'il n'est pas soumis, rediriger vers la vue de la partie
        return $this->redirectToRoute('app_show_party', ['id' => $party->getId()]);
    }

}