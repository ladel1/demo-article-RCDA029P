<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\DemoAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin-inscription", name="app_admin")
     */
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, DemoAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {

        $user = new User();
        $form =  $this->createForm(RegistrationFormType::class,$user);
        // roles
        $form->add("roles",ChoiceType::class,[
            "mapped"=>false,
            'choices'  => [
                'USER' => 'ROLE_USER',
                'ADMIN' => 'ROLE_ADMIN',
                'MANAGER' => 'ROLE_MANAGER',
            ]            
        ]);
        
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$form->get('roles')->getData()]);
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }



        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/utilisateurs", name="app_admin_users")
     */
    public function userManager(){
        return $this->render("admin/list-user.html.twig");
    }
}
