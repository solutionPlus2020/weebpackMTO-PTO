<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\Produits;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/fournisseur")
 */
class FournisseurController extends AbstractController
{
    /**
     * @Route("/", name="fournisseur_index", methods={"GET"})
     * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')",message="Vous n'avez pas le droit d'accéder")
     */
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->render('fournisseur/index.html.twig', [
            'fournisseur' => $fournisseurRepository->findAll(),

        ]);
    }

    /**
     * @Route("/new", name="fournisseur_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')",message="Vous n'êtes pas un administrateur")
     *
     */
    public function new(Request $request): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('fournisseur_index');
        }

        return $this->render('fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fournisseur_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')",message="Vous n'êtes pas un administrateur")
     */
    public function show(Fournisseur $fournisseur): Response
    {
        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur,
        ]);
    }

    /**
     *
     * @Route("/{id}/edit", name="fournisseur_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')",message="Vous n'êtes pas un administrateur")
     */
    public function edit(Request $request, Fournisseur $fournisseur): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fournisseur_index');
        }

        return $this->render('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fournisseur_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN')",message="Vous n'êtes pas un administrateur")
     */
    public function delete(Request $request, Fournisseur $fournisseur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fournisseur_index');
    }


    public function getProduitsMTOs()
{
    $pto= $this
        ->getDoctrine()
        ->getRepository(Produits::class)
        ->findBy(fournisseur|1);
}
    public function getProduitsPTOs()
    {
        $pto= $this
            ->getDoctrine()
            ->getRepository(Produits::class)
            ->findBy(fournisseur|2);
    }

    /**
     * @Route("/PTO/Produits", name="fournisseur_PTO")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_AGENCE') or is_granted('ROLE_USER')",message="Vous n'êtes pas connectée")
     */
    public function getProduitsPTO(): Response
    {
        return $this->render('fournisseur/PTO.html.twig', [

            'PTO'=>$this->getProduitsPTOs()
        ]);
    }
    /**
     * @Route("/MTO/Produits", name="fournisseur_MTO")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_AGENCE') or is_granted('ROLE_USER')",message="Vous n'êtes pas connectée")
     */
    public function getProduitsMTO(): Response
    {
        return $this->render('fournisseur/MTO.html.twig', [

            'MTO'=>$this->getProduitsMTOs()
        ]);
    }
}
