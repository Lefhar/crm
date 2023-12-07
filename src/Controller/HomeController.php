<?php

namespace App\Controller;

use App\Entity\Config;
use App\Repository\ConfigRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index( ConfigRepository $configRepository): Response
    {
        // Trouver tous les utilisateurs avec le rôle "ROLE_ADMIN"
        $configForFirstAdmin = $configRepository->findConfigForFirstAdmin();

        return $this->render('home/index.html.twig', [
            'config' => $configForFirstAdmin,
            'controller_name' => 'HomeController',
        ]);
    }
}
