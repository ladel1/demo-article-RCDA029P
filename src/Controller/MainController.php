<?php 

namespace App\Controller;

use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController{

    /**
     * @Route("/",name="home")
     */
    function home(Mailer $mailer):Response{  
      $mailer->send("support@eni.fr","adel@eni.fr","test1","balbalablabalabal");
      $articles = [
         "Samsung S21",
         "Iphone 11",
         "Nokia 3310",
         "PC Dell I7"
      ];
      $prenom = "adel";
       return $this->render("home.html.twig",compact("articles","prenom"));
    }

    /**
     * @Route("/contact",name="contact")
     */
    function contact():Response{     
        return $this->render("contact.html.twig");
     }

}