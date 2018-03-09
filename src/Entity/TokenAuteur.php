<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 05/03/2018
 * Time: 17:24
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenAuteurRepository")
 */
class TokenAuteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Auteur", inversedBy="resetPasswordTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateValidite;

    /**
     * @ORM\Column(type="string")
     */
    private $token;

    public function __construct()
    {
        $date = new \DateTime();
        $this->dateValidite = $date->add(new \DateInterval('PT1H'));
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param mixed $auteur
     */
    public function setAuteur($auteur): void
    {
        $this->auteur = $auteur;
    }

    /**
     * @return mixed
     */
    public function getDateValidite()
    {
        return $this->dateValidite;
    }

    /**
     * @param mixed $dateValidite
     */
    public function setDateValidite($dateValidite): void
    {
        $this->dateValidite = $dateValidite;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }
}