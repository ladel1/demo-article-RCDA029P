<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{

    /**
     * @Route("/", name="app_profil_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProfilRepository $profilRepository,UserRepository $userRepository): Response
    {
        $profil = null;
        if($this->getUser()->getProfil()){
            $profil = $this->getUser()->getProfil();
        }else{
            $profil = new Profil();
        }
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $profilRepository->add($profil, true);
            $user = $this->getUser();
            $user->setProfil($profil); // c'est pas une erreur
            $userRepository->add($user,true);
            return $this->redirectToRoute('app_profil_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/new.html.twig', [
            'profil' => $profil,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_profil_show", methods={"GET"})
     */
    public function show(Profil $profil): Response
    {
        return $this->render('profil/show.html.twig', [
            'profil' => $profil,
        ]);
    }

   
    /**
     * @Route("/{id}", name="app_profil_delete", methods={"POST"})
     */
    public function delete(Request $request, Profil $profil, ProfilRepository $profilRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profil->getId(), $request->request->get('_token'))) {
            $profilRepository->remove($profil, true);
        }

        return $this->redirectToRoute('app_profil_index', [], Response::HTTP_SEE_OTHER);
    }
}
