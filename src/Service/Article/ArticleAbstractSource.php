<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 15:48
 */

namespace App\Service\Article;


use App\Entity\Article;

abstract class ArticleAbstractSource implements ArticleRepositoryInterface
{
    protected $mediator;

    public function setCatalogue(ArticleCatalogue $catalogue)
    {
           $this->mediator = $catalogue;
    }

    abstract protected function buildFromArray(iterable $article): ?Article;
}