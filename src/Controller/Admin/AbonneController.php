<?php

namespace App\Controller\Admin;

use App\Entity\Abonne;
use App\Form\AbonneType;
use App\Repository\AbonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/abonne')]
class AbonneController extends AbstractController
{
    #[Route('/', name: 'app_admin_abonne_index', methods: ['GET'])]
    public function index(AbonneRepository $abonneRepository): Response
    {
        return $this->render('admin/abonne/index.html.twig', [
            'abonnes' => $abonneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_abonne_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $hasher): Response
    {
        $abonne = new Abonne();
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // rajout de l'encodage du mot de passe , on aura ajouté UserPasswordHasherInterface $hasher
            if($mdp = $form->get("password")->getData()){
                $mdpEncode = $hasher->hashPassword($abonne,$mdp);
                $abonne->setPassword( $mdpEncode );
            }
            // fin mdp encodage
            

            $entityManager->persist($abonne);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonne/new.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_abonne_show', methods: ['GET'])]
    public function show(Abonne $abonne): Response
    {
        return $this->render('admin/abonne/show.html.twig', [
            'abonne' => $abonne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_abonne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Abonne $abonne, EntityManagerInterface $entityManager,UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(AbonneType::class, $abonne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // rajout de l'encodage du mot de passe , on aura ajouté UserPasswordHasherInterface $hasher
            if($mdp = $form->get("password")->getData()){
                $mdpEncode = $hasher->hashPassword($abonne,$mdp);
                $abonne->setPassword( $mdpEncode );
            }
            // fin mdp encodage


            $entityManager->flush();

            return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/abonne/edit.html.twig', [
            'abonne' => $abonne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_abonne_delete', methods: ['POST'])]
    public function delete(Request $request, Abonne $abonne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($abonne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_abonne_index', [], Response::HTTP_SEE_OTHER);
    }
}
