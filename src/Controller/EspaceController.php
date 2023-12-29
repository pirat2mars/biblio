<?php

namespace App\Controller;


use DateTime;
use App\Entity\Livre;
use App\Entity\Emprunt;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/espace-lecteur', name: 'app_espace')]
class EspaceController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(SessionInterface $session): Response
    {

        $reservations=$session->get("reservations",[]);

        return $this->render('espace/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }


    #[Route('/reserver-livre-{id}', name: '_reserver',requirements: [ "id" => "[0-9]+" ])]
    public function reserver(Livre $livre,SessionInterface $session): Response
    {
        $reservations=$session->get("reservations",[]);
        $dejaReserve = false;
        foreach($reservations as $key => $resa){
            if($livre->getId() == $resa["livre"]->getId()){
                $dejaReserve = true;
            }
        }
        if (!$dejaReserve){
            $reservations [] = ["livre" => $livre, "date" => date("Y-m-d")];
        }
        
        $session->set("reservations",$reservations);
        return $this->redirectToRoute("app_espace_index");

    }


    #[Route('/supprimer-reservation-{id}', name: '_supprimer_reservation',requirements: [ "id" => "[0-9]+" ])]
    public function supprimerReservation(Livre $livre,SessionInterface $session): Response
    {
        $reservations = $session->get("reservations", []);
    
        foreach ($reservations as $key => $resa) {
            if ($livre->getId() == $resa["livre"]->getId()) {
                // Supprimer l'élément de la session
                unset($reservations[$key]);
            }
        }
        
        $session->set("reservations", $reservations);
        return $this->redirectToRoute("app_espace_index");
    
    }


    #[Route('/emprunter', name: '_emprunter_des_livres')]
    public function EmprunterDesLivres(SessionInterface $session,EntityManagerInterface $entityManager): Response
    {
        

        $reservations = $session->get("reservations", []);
    
        foreach ($reservations as $key => $resa) {

            $emprunt = new Emprunt();
            $emprunt->setDateSortie(new DateTime("now"));
            $emprunt->setLivre($resa["livre"]);
            $emprunt->setAbonne($this->getUser());

            // Stocker l'emprunt en base de données
            $entityManager->persist($emprunt);
            $entityManager->flush();

            unset($reservations[$key]);
            
        }
        
        $session->set("reservations", "");
        return $this->redirectToRoute("app_espace_index");
    
    }



    
/**
 * $this-getUser pour recupere les informations de l'utilisateur connecté
 * Creer la route pour supprimer un livre des reservations en session
 * Creer la route pour transformer les reservations en emprunt, les reservations doivent etre vide apres. set a vide pour vider la variable de session , remove
 */

}
