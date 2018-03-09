<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 15:39
 */

namespace App\Service\Article;


use App\Entity\Article;
use App\Exception\DuplicateCatalogueDataException;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;

class ArticleCatalogue implements ArticleCatalogueInterface
{

    /**
     * LEs différentes sources de donnée de mon médiateur.
     */
    private $sources;


    public function addSource(ArticleAbstractSource $source): void
    {
        $this->sources[] = $source;
    }

    public function setSources(iterable $source): void
    {
        $this->sources = $source;
    }

    public function getSources(): iterable
    {
        return $this->sources;
    }

    public function find($id): ?Article
    {
        $result = null;
        /** @var $source ArticleAbstractSource */
        foreach ($this->getSources() as $source) {
            $article = $source->find($id);
            if($article != null) {
               $result[] = $article;
            }
        }
        // Si il y a plus de deux resultat
        if(count($result) > 1) {
            throw new DuplicateCatalogueDataException(sprintf("Return value of %s cannot return more than one record",
                get_class($this)));
        }

        return array_pop($result);
    }

    public function findAll(): ?iterable
    {
      return $this->iterateOverSources('findAll');
    }

    public function findLastFive(): ?iterable
    {
        // Possible que ça merge les tableau si ils ont les mêmes lastfive
        return array_reverse($this->iterateOverSources('findLastFive')->slice(-5));
    }

    /**
     * @param $functionToCall
     * @return ArrayCollection
     */
    private function  iterateOverSources ($functionToCall) : ArrayCollection
    {
        $articles = [];
        /** @var $source ArticleAbstractSource */
        /** @var  $article Article */
        foreach ($this->getSources() as $source) {
            foreach ($source->$functionToCall() as $article) {
                if(!array_key_exists($article->getId(), $articles)) {
                    $articles[$article->getId()] = $article;
                }
            }
        }
        return new ArrayCollection($articles);
    }

    /**
     * Retourne le nombre d'éléments de chaque source.
     * @return int
     */
    public function count(): int
    {
        return count($this->sources);
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getStats(): array
    {
        $stats = [];
        $stats[(new ReflectionCLass(get_class($this)))->getShortName()] = $this->count();
        /** @var $source ArticleAbstractSource */
        foreach ($this->getSources() as $source) {
            $name = (new ReflectionCLass(get_class($source)))->getShortName();
            $stats[$name] = $source->count();
        }

        return $stats;
    }
}