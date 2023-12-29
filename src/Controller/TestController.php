<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/test/essai', name: 'app_test_essai')]
    public function essai(): Response
    {
        return $this->render('test/essai.html.twig', [ "prenom" => "Guillaume" ]);
    }



    /**
     * EXO : ajouter une route pour le chemin /test/calcul
     *          qui affiche 
     *              "le résultat du calcul de 5 + 2 est 7"
     *      Il faut que cette phrase s'affiche entre le menu et le footer
     */
    /**
     * Dans le chein de ma route je met mes valeurs
     * {b?} b peut etre optionnel
     * 
     * requirements:["a" => "[0-9]+"] permet de contraindre les entrees dans l'url de seulement de chiffre regex
     * idem pour b mais la avec d+ pour digit "b" => "\d+" 
     * 
     * 
     * 
     * 
     */

    #[Route('/test/calcul/{a}/{b?}', name: 'app_test_calcul',requirements:["a" => "[0-9]+" , "b" => "\d+"])]
    public function calcul(int $a,int $b = null): Response
    {
        //$a = 12;
        //$b = 5;
        $b = $b ?? 10;  // si b est null alors b = 10
        return $this->render('test/calcul.html.twig' , ["nb1" => $a, "nb2" => $b]);
    }



    #[Route('/test/tableau', name: 'app_test_tableau')]
    public function tableau(): Response
    {
        $intTab = [5,7,-10,12,53,125];


        $personne = [
            "nom" => "dupont",
            "prenom" => "gérard",
            "age" => 59
        ];


        $personnes[] = $personne;

        $personnes[] = [
            "nom"       =>  "mentor",
            "prenom"    =>  "gérard",
            "age"       =>  30,
        ];
        $personnes[] = [
            "nom"       =>  "ateur",
            "prenom"    =>  "nordine",
            "age"       =>  40,
        ];
        $personnes[] = [
            "nom"       =>  "onym",
            "prenom"    =>  "anne",
            "age"       =>  22,
        ];






        return $this->render('test/tableau.html.twig', ["tab" => $intTab , "personne" => $personne , "personnes" => $personnes]);
    }


    #[Route('/test/objets', name: 'app_test_objets')]
    public function objets()
    {
        $personne =  new \stdClass;
        $personne->nom = "cérien";
        $personne->prenom = "jean";
        $personne->age = 55;

        return $this->render('test/objets.html.twig', [ "personne" => $personne ]);
    }




}
