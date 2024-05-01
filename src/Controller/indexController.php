<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class indexController extends AbstractController
{
    #[Route(path:'/', name: 'index')]
    public function index(): Response
    {
       return $this->render('index.html.twig');
    }
}