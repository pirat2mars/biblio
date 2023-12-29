<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche',methods:["GET"])]
    public function index(Request $request,LivreRepository $livreRepository): Response
    {

        if($request->isMethod("GET")){
            $mot=$request->query->get("search");

            // $livre = $livreRepository->findBy(["titre" => $mot]);
            // $livre = $livreRepository->findByTitre($mot);

            

            // dd($livre);
        }
        return $this->render('recherche/index.html.twig', [
            'livres' => $livreRepository->findByTitre($mot),
            'mot' => $mot,
        ]);
    }
}
