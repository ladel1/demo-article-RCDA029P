<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Form\UserProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function index(): Response
    {

        $profil = new Profil();
        $user = new User();

        $UserProfil = [
            "profil"=>$profil,
            "user"=>$user
        ];

        $formUserProfil = $this->createForm(UserProfilType::class,$UserProfil);



        return $this->render('test/index.html.twig', [
            'form' => $formUserProfil->createView(),
        ]);
    }
}
