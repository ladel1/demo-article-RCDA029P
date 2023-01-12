<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @Route("/article", name="app_article_")
 */
class ArticleController extends AbstractController
{
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }


    /**
     * @Route("/", name="list")
     */
    public function index(): Response
    {


        return $this->render('article/list.html.twig', [
           "articles"=> $this->articleRepository->findBy(["isPublished"=>true])
        ]);
    }

    /**
     * @Route("/authors", name="list_authors")
     */
    public function listAuthors(): Response
    {
        return $this->render('article/list-author.html.twig', [
           "authors"=> $this->articleRepository->findAllAuthor()
        ]);
    }

    /**
     * @Route("/ajouter", name="add")
     */
    public function add(Request $request): Response
    {
        // Afficher formulaire
        // création de l'instance article
        $article = new Article();
        // 2. Création formulaire 
        $formArticle = $this->createForm(ArticleType::class,$article);
        // 3. gérer la requette
        $formArticle->handleRequest($request);
        // 4. 
        if($formArticle->isSubmitted()){            
            $this->articleRepository->add($article,true);
            $this->addFlash("success","L'article a bien été ajouté!");
            return $this->redirectToRoute("app_article_detail",["id"=>$article->getId()]);
        }
        return $this->render("article/add.html.twig",["articleForm"=>$formArticle->createView()]);
    }   
    /**
     * @Route("/ajouter-old", name="add_old")
     */
    public function oldAdd(Request $request): Response
    {
        //$request->get("title")
        if($request->isMethod("POST")){ // sans verification
            $article = new Article();
            $article->setTitle($request->get("title"))
                    ->setContent($request->get("content"))
                    ->setImage($request->get("image"))
                    ->setAuthor($request->get("author"))
                    ->setDatePublished(new DateTime())
                    ->setIsPublished(true);
            $this->articleRepository->add($article,true);
            return $this->redirectToRoute("app_article_detail",["id"=>$article->getId()]);

        }

        return $this->render("article/add.html.twig");
    }   

    /**
     * @Route("/{id}",name="detail",requirements={"id":"\d+"})
     */
    public function detail(Article $article){        
        return $this->render("/article/detail.html.twig",["article"=> $article ]);
    }
    
    /**
     * @Route("/auteur/{name}",name="author")
     */
    public function articlesAuthor($name){  
        return $this->render('article/list.html.twig', [
            "articles"=> $this->articleRepository->findByAuthor($name),
            "author"=>$name
         ]);       
    }


    /**
     * @Route("/modifier/{id}",name="edit",requirements={"id":"\d+"})
     */
    public function edit(Article $article,Request $request){
        $articleForm = $this->createForm(ArticleType::class,$article);
        $articleForm->handleRequest($request);

        if($articleForm->isSubmitted() && $articleForm->isValid() ){
            $this->articleRepository->update();
            $this->addFlash("success","L'article a bien été modifié!");
            return $this->redirectToRoute("app_article_detail",["id"=>$article->getId()]);
        }

        return $this->render("article/update.html.twig",
        ["articleForm"=>$articleForm->createView()]);
    }


    /**
     * @Route("/supprimer",name="delete")
     */
    public function delete(Request $request){        
        $id = $request->get("id");
        if($this->isCsrfTokenValid("delete-".$id,$request->get("token"))){
            $this->articleRepository->remove($this->articleRepository->find($id),true);
            $this->addFlash("success","L'article a bien été supprimé!");
            return $this->redirectToRoute("app_article_list");
        }
        $this->addFlash("danger","Erreur dans CSRF Token");
        return $this->redirectToRoute("app_article_detail",["id"=>$id]);
    }


    
    
 

}
