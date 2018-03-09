<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 07/03/2018
 * Time: 15:56
 */

namespace App\Service\Article;


use App\Entity\Article;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineSource extends ArticleAbstractSource
{

    private $em;
    private $entity = Article::class;

    /**
     * DoctrineSource constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        return $this->em->getRepository($this->entity)->find($id);
    }

    /**
     * @return iterable
     */
    public function findAll(): iterable
    {
        return $this->em->getRepository($this->entity)->findAll();
    }

    public function findLastFive(): ?iterable
    {
        $result = $this->em->getRepository($this->entity)->findLastFiveArticles();
        return $result;
    }


    protected function buildFromArray(iterable $article): ?Article
    {
        return null;
    }


}