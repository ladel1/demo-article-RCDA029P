<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController{

    /**
     * @Route("/")
     */
    function home():Response{     
       return $this->render("home.html.twig");
    }

    /**
     * @Route("/contact")
     */
    function contact():Response{     
        return $this->render("contact.html.twig");
     }

}