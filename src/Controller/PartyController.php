<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Party;
use App\Entity\Sport;
use App\Form\CommentType;
use App\Form\Type\EditPartyFormType;
use App\Form\Type\PartyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/party')]
class PartyController extends AbstractController
{

    #[Route(path: '/list', name: 'app_party_user_list')]
    public function listUser(EntityManagerInterface $entityManager): Response
    {
        $sports = $entityManager->getRepository(Sport::class)->findAll();
        $partys = $entityManager->getRepository(Party::class)->findAll();
        return $this->render('Pages/Party/User/list.html.twig', [
            'partys' => $partys,
            'sports' => $sports,
        ]);
    }
    #[Route(path: '/admin/list', name: 'app_party_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $partys = $entityManager->getRepository(Party::class)->findAll();
        return $this->render('Pages/Party/Admin/list.html.twig', [
            'partys' => $partys,
        ]);
    }

    #[Route(path: '/admin/create/{id}', name: 'app_party_create', defaults: ['id' => null])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $party = new Party();
        $form = $this
            ->createForm(PartyFormType::class, $party)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($party);
            $entityManager->flush();
        }
        return $this->render('Pages/Party/Admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('admin/{id}/edit', name: 'app_party_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Party $party, EntityManagerInterface $entityManager): Response
    {
        // Créer un formulaire pour modifier les détails du match
        $form = $this->createForm(EditPartyFormType::class, $party);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers la page des détails du match modifié
            return $this->redirectToRoute('app_show_party', ['id' => $party->getId()]);
        }

        return $this->render('Pages/Party/Admin/edit.html.twig', [
            'party' => $party,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_show_party')]
    public function show(EntityManagerInterface $entityManager,  int $id): Response
    {
        $sports = $entityManager->getRepository(Sport::class)->findAll();
        $party = $entityManager->getRepository(Party::class)->find($id);
        if (!$party) {
            throw $this->createNotFoundException('Match non trouvé');
        }

        $comments = $entityManager->getRepository(Comments::class)->findBy(['party' => $party]);

        $comment = new Comments();
        $comment->setParty($party);

        if ($party->getStatus() === 'en cours' && $party->getHomeScore() === null && $party->getAwayScore() === null) {
            $party->setHomeScore(0);
            $party->setAwayScore(0);
        }

        if ($party->getStatus() === 'terminé' && $party->getHomeScore() === null && $party->getAwayScore() === null) {
            $party->setHomeScore(0);
            $party->setAwayScore(0);
        }

        $entityManager->flush(); // Sauvegarder les changements en base de données

        $form = $this->createForm(CommentType::class, $comment);

        // Calculer les minutes écoulées depuis le début du match
        $currentTime = new \DateTime();
        $minutesElapsed = $party->getTime()->diff($currentTime)->format('%i');


        return $this->render('Pages/Party/User/show.html.twig', [
            'party' => $party,
            'sports' => $sports,
            'comments' => $comments,
            'minutesElapsed' => $minutesElapsed,
            'form' => $form->createView(), // Passer le formulaire à la vue
        ]);
    }

}