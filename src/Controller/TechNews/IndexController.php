<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 26/02/2018
 * Time: 14:24
 */

namespace App\Controller\TechNews;

use App\Entity\Article;
use App\Entity\Auteur;
use App\Entity\Category;
use App\Exception\DuplicateCatalogueDataException;
use App\Service\Article\ArticleCatalogue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class IndexController extends Controller
{

    /**
     * @param ArticleCatalogue $catalogue
     * @return Response
     *
     * @Route("/{_locale}")
     */
    public function index(ArticleCatalogue $catalogue)
    {
        //$articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $articles = $catalogue->findAll();
        $spotlights = $this->getDoctrine()->getRepository(Article::class)->findSpotlightArticles();

        return $this->render('index/index.html.twig',[
            "articles" => $articles,
            'spotlights' => $spotlights,

        ]);
    }

    /**
     * @Route("/{_locale}/category/{libelle}",
     *     name="index_category",
     *     methods={"GET"},
     *     requirements={
     *     "libelle":"\w+",
     *     "_locale": "en|fr"
     * })
     */
    public function category ($libelle = "computing")
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['libelle' => $libelle]);
        $articles = $category->getArticles();
        return $this->render('index/category.html.twig',[
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/{_locale}/{category}/{slug}_{id}.html" ,
     *      name="index_article" ,
     *      requirements={
     *     "id": "\d+",
     *     "_locale": "en|fr"
     * }
     * )
     * @param $id
     * @param ArticleCatalogue $catalogue
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function article ($id, ArticleCatalogue $catalogue)
    {
        $catalogue->findAll();
        try {
            $article = $catalogue->find($id);
        }catch ( DuplicateCatalogueDataException $catalogueDataException) {
            return $this->redirectToRoute('index', [],Response::HTTP_MOVED_PERMANENTLY);
        }

        $repo = $this->getDoctrine()->getRepository(Article::class);
        $suggestions = $repo->findArticleSuggestions($article->getId(),$article->getCategory()->getId());
        return $this->render('index/article.html.twig',[
            'article' => $article,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * @param Auteur $auteur
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/_locale/auteur/{nom}-{prenom}",
     *     name="index_auteur",
     *     requirements={
            "_locale": "en|fr"
*     }
     * )
     */
    public function auteur(Auteur $auteur)
    {
        return $this->render('index/auteur.html.twig',[
            'auteur' => $auteur
        ]);
    }

    public function sidebar (ArticleCatalogue $catalogue)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);

        $articles = $catalogue->findLastFive();
        $special = $repository->findSpecialArticles();

        return $this->render('components/_sidebar.html.twig',[
           'articles' => $articles,
           'special' => $special
        ]);
    }

}