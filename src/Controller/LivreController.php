<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    #[Route('/livres/empruntes', name: 'app_livre_empruntes')]
    public function empruntes(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findLivresEmpruntes(),
            'titre' => "Livres empruntés"
        ]);
    }

    #[Route('/livres/disponibles', name: 'app_livre_disponibles')]
    public function disponibles(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findLivresDisponibles(),
            'titre' => "Livres disponibles"
        ]);
    }


    
}
