<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 06/03/2018
 * Time: 09:57
 */

namespace App\Repository;

use App\Entity\TokenAuteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TokenAuteurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TokenAuteur::class);
    }


}