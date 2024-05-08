<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route(path:'/admin')]
class dashboardController extends AbstractController
{
    #[Route(path:'/dashboard', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }
}