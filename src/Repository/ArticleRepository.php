<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /**
     * Retourne les 5 derniers articles en base
     * @return mixed
     */
    public function findLastFiveArticles ()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne des suggestions d'articles d'une categorie sans l'article que l'on regarde
     * @param $idarticle
     * @param $idcategorie
     * @return mixed
     */
    public function findArticleSuggestions($idarticle, $idcategorie)
    {
        return $this->createQueryBuilder('a')
            ->where('a.category = :category')->setParameter('category' , $idcategorie)
            ->andWhere('a.id != :article_id')->setParameter('article_id', $idarticle)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recupère tous les articles en spotlight
     * @return mixed
     */
    public function findSpotlightArticles ()
    {
        return $this->createQueryBuilder('a')
            ->where('a.spotlight = :spotlight')
            ->setParameter('spotlight', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère tous les articles en spécial
     * @return mixed
     */
    public function findSpecialArticles ()
    {
        return $this->createQueryBuilder('a')
            ->where('a.special = :special')
            ->setParameter('special', true)
            ->getQuery()
            ->getResult();
    }
}
