<?php

namespace App\Controller\TechNews;

use App\Controller\Helper;
use App\Entity\Article;
use App\Entity\Auteur;
use App\Entity\Category;
use App\Form\Type\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    use Helper;
    /**
     * @Route("/articles", name="tech_news_article")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $category = new Category();
        $category->setLibelle("Ma catégorie");
        $em->persist($category);

        $auteur = new Auteur();
        $auteur->setNom("COUBARD");
        $auteur->setPrenom("ALexis");
        $auteur->setEmail("test@test.com");
        $auteur->setPassword("mon super password");
        $auteur->setRoles("un role");
        $em->persist($auteur);

        $article = new Article();
        $article->setTitre("Mon super tittre");
        $article->setContenu("loremp ipsum");
        $article->setSpecial(true);
        $article->setSpotlight(true);
        $article->setFeaturedImage("mon image");
        $article->setCategory($category);
        $article->setAuteur($auteur);
        $em->persist($article);

        $em->flush();



        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     *  @Route("/creer-un-article", name="article_add")
     *  @Security("has_role('ROLE_AUTEUR')")
     */
    public function addArticle (Request $request)
    {
        // Création d'un nouvel article
        $article = new Article();;
        // Auteur
        $auteur = $this->getDoctrine()
            ->getRepository(Auteur::class)
            ->find(1);
        $article->setAuteur($auteur);
        // Création d'un formulaire
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() &&$form->isValid()) {
            /** @var Article $article */
            $article = $form->getData();
            // Recup l'image
            /** @var File $image */
            $image = $article->getFeaturedImage();
            $fileName = $this->slugify($article->getTitre()) .'.'. $image->guessExtension();

            $image->move(
                $this->getParameter('articles_directory'),
                $fileName
            );

            $article->setFeaturedImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('index_article', [
                'category' => $this->slugify($article->getCategory()->getLibelle()),
                'slug' => $this->slugify($article->getTitre()),
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/ajouterarticle.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
