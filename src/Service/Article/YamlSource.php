<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 15:56
 */

namespace App\Service\Article;


use App\Article\YamlProvider;
use App\Entity\Article;
use App\Entity\Auteur;
use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;

class YamlSource extends ArticleAbstractSource
{

    private $yamlProvider;
    private $articles;

    public function __construct(YamlProvider $provider)
    {
        $this->yamlProvider = $provider;
        $this->articles = $provider->getArticles();
    }

    /**
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        $articlesId = array_column($this->articles,'id');
        $key = array_search($id, $articlesId);


        if (!$key) {
            return null;
        }

        $tmp = $this->articles['article'.++$key];

        return $this->buildFromArray($tmp);
    }

    /**
     * @return iterable
     */
    public function findAll(): iterable
    {
        $articles = [];
        foreach ($this->articles as $article){
            $tmp = $article;
            $articles[] = $this->buildFromArray($tmp);
        }
        return $articles;
    }


    public function findLastFive(): ?iterable
    {
        return array_slice($this->findAll(),-5);
    }


    /**
     * @param iterable $article
     * @return Article
     */
    protected function buildFromArray(iterable $article): ?Article
    {
        $tmp = (object) $article;
        // Category
        $category = new Category();
        $category->setId($tmp->categorie['id']);
        $category->setLibelle($tmp->categorie['libelle']);

        // Auteur
        $auteur = new Auteur();
        $auteur->setId($tmp->auteur['id']);
        $auteur->setPrenom($tmp->auteur['prenom']);
        $auteur->setNom($tmp->auteur['nom']);
        $auteur->setEmail($tmp->auteur['email']);

        $article = new Article();
        $article->setId($tmp->id);
        $article->setTitre($tmp->titre);
        $article->setContenu($tmp->contenu);
        $article->setFeaturedImage($tmp->featuredimage);
        $article->setAuteur($auteur);
        $article->setCategory($category);
        $article->setSpotlight($tmp->spotlight);
        $article->setSpecial($tmp->special);
        $article->setDateCreation($tmp->datecreation);

        return $article;
    }
}