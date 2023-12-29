<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/admin/livre')]
class LivreController extends AbstractController
{
    #[Route('/', name: 'app_admin_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {


        $livresDispo = $livreRepository->findLivresDisponibles();
        $livresEmprunte = $livreRepository->findLivresEmpruntes();


        return $this->render('admin/livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
            'livresDisponibles' => $livresDispo,
            'livresEmpruntes' => $livresEmprunte
        ]);
    }

    #[Route('/new', name: 'app_admin_livre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //rajout de la recuperation de l'upload fichier
            if ($fichier = $form->get("couverture")->getData()){
                //recuperatio du nom du fichier uploadé
                $nomFichier = pathinfo($fichier->getClientOriginalName(),PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug( $nomFichier );

                // fonction uniqid qui genere un identifiant unique sur le serveur sur lequel on est, pratique
                $nouveauNomFichier .= "_" . uniqid() . "." . $fichier->guessExtension();
                $fichier->move("images", $nouveauNomFichier);
                $livre->setCouverture($nouveauNomFichier);
            }


            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('admin/livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //rajout de la recuperation de l'upload fichier
            if ($fichier = $form->get("couverture")->getData()){
                //recuperatio du nom du fichier uploadé
                $nomFichier = pathinfo($fichier->getClientOriginalName(),PATHINFO_FILENAME);
                $slugger = new AsciiSlugger();
                $nouveauNomFichier = $slugger->slug( $nomFichier );

                // fonction uniqid qui genere un identifiant unique sur le serveur sur lequel on est, pratique
                $nouveauNomFichier .= "_" . uniqid() . "." . $fichier->guessExtension();
                $fichier->move("images", $nouveauNomFichier);

                // supprimer l'ancien fichier si il existe
                $ancienFichier = "images/" . $livre->getCouverture();
                if( file_exists($ancienFichier)){
                    unlink($ancienFichier);
                }

                $livre->setCouverture($nouveauNomFichier);
            }



            $entityManager->flush();

            return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
