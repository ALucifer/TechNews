<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 15:25
 */

namespace App\Service\Article;


use App\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;

interface ArticleRepositoryInterface
{
    /**
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article;

    /**
     * @return iterable
     */
    public function findAll(): ?iterable;

    /**
     * @return iterable|null
     */
    public function findLastFive(): ?iterable;

    // public function findBy();

    // public function findOneBy();

}