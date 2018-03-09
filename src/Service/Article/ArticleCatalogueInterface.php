<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 15:34
 */

namespace App\Service\Article;


interface ArticleCatalogueInterface extends ArticleRepositoryInterface
{
    public function addSource(ArticleAbstractSource $source): void;
    public function setSources(iterable $source): void;
    public function getSources(): iterable;
}