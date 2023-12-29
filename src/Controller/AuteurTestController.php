<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurTestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
// Classe pour recuperation du post etc...
use Symfony\Component\HttpFoundation\Request;

class AuteurTestController extends AbstractController
{
    #[Route('/auteur/test', name: 'app_auteur_test')]
    public function index(AuteurRepository $auteurRepository): Response
    {

        $listes = $auteurRepository->findAll();

        return $this->render('auteur_test/index.html.twig', [
            'auteurs' => $listes,
        ]);
    }

    #[Route('/auteur/test/ajouter', name: 'app_auteur_test_ajouter')]
    public function ajouter(Request $request,EntityManagerInterface $entityManager): Response
    {

        // dump() remplace var_dump
        // dd() : dump & die
        //dd($request);
        /*
          L'objet de la classe Request a des propriétés publiques de type objet qui contiennent toutes 
        ?  les valeurs des variables superglobales de PHP.
        ?       $request->query         contient        $_GET
        ?       $request->request       contient        $_POST
        ?       $request->files         contient        $_FILES
        ?       $request->server        contient        $_SERVER
        ?       $request->cookies       contient        $_COOKIES
        ?       $request->session       contient        $_SESSION
        ?   Ces différents objets ont des méthodes communes : get, has,...    
        ?   La méthode get() permet de récupérer la valeur voulue.
        ?   𝒆̲̅𝒙̲̅ : $motRecherche = $request->query->get("search");  
        ?        $motRecherche = $_GET["search"]
        */

        if($request->isMethod("POST")){
            $nom=$request->request->get("nom");
            $prenom=$request->request->get("prenom");

            //verification si nom n'est pas vide
            if($nom){
            
                //on creer un objet auteur
            $auteur= new Auteur;
                $auteur->setNom($nom);
                $auteur->setPrenom($prenom);

                // on fait appel a l'EntityManagerInterface avec $entityManager
                //on utilise persist pour preparer a la requete insert avec les données de l'objet
                $entityManager->persist($auteur);
                //ebsuite on va executer les requetes en attentes avec la methode flush
                $entityManager->flush();


                // on ajoute un message flash
                $this->addFlash("success","Nouvel auteur enregistré avec succes");

                //redirection vers le nom de la route choissie
                return $this->redirectToRoute("app_auteur_test");

            }
            else{
                $this->addFlash("danger","Nom obligatoire");
            }
        }


        return $this->render('auteur_test/formulaire.html.twig');
    }



    #[Route('/auteur/test/modifier/{id}', name: 'app_auteur_test_modifier',requirements:["id" => "[0-9]+"])]
    public function modifier(Request $request,EntityManagerInterface $entityManager, int $id,AuteurRepository $auteurRepository): Response
    {

        $auteur=$auteurRepository->find($id);
        if($request->isMethod("POST")){
            $nom=$request->request->get("nom");
            $prenom=$request->request->get("prenom");
        
            //verification si nom n'est pas vide
            if($nom){
                $auteur->setNom($nom);
                $auteur->setPrenom($prenom);

                //pas besoin d'utiliser persist, toutes les entités sont recuerere de la bdd sont surveille par l'entitymanager
                // des que l'on va utiliser flush la requete update va etre executé
                $entityManager->flush();

                $this->addFlash("success","Auteur modifié avec succes");

                //redirection vers le nom de la route choissie
                return $this->redirectToRoute("app_auteur_test");
            }
            else{
                $this->addFlash("danger","Nom obligatoire");
            }    
        }

        return $this->render('auteur_test/formulaire.html.twig', ['auteur' => $auteur]);

    }




    #[Route('/auteur/test/add', name: 'app_auteur_test_add')]
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteur;
        $form = $this->createForm(AuteurTestType::class,$auteur);

        //l'objet form va gerer la requete HTTP
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $entityManager->persist($auteur);
            $entityManager->flush();

            $this->addFlash("success","Nouvel auteur enregistré avec succes");

            return $this->redirectToRoute("app_auteur_test");
        }
        //On render sur formulaire qui aura les données formAuteur et on y passe $form->createView , 
        return $this->render('auteur_test/form.html.twig', ['formAuteur' => $form->createView()]);
    }




    #[Route('/auteur/test/update/{id}', name: 'app_auteur_test_update',requirements:["id" => "[0-9]+"])]
    public function update(Request $request,EntityManagerInterface $entityManager,Auteur $auteur): Response
    {

        $form = $this->createForm(AuteurTestType::class,$auteur);

        //l'objet form va gerer la requete HTTP
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $entityManager->flush();

            $this->addFlash("success","Auteur modifié avec succes");

            return $this->redirectToRoute("app_auteur_test");
        }
        //On render sur formulaire qui aura les données formAuteur et on y passe $form->createView , 
        return $this->render('auteur_test/form.html.twig', ['formAuteur' => $form->createView()]);

    }



}
