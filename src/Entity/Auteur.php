<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuteurRepository")
 * @UniqueEntity(fields={"email"} , errorPath="email", message="Ce compte existe déjà !" )
 */
class Auteur implements  AdvancedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string" , length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $derniereConnexion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="auteur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TokenAuteur", mappedBy="auteur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $resetPasswordTokens;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TokenAuteur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $activeAccountToken;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->dateInscription = new \DateTime();
        $this->articles = new ArrayCollection();
        $this->isActive = false;
        $this->resetPasswordTokens = new ArrayCollection();
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
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * @param mixed $dateInscription
     */
    public function setDateInscription($dateInscription): void
    {
        $this->dateInscription = $dateInscription;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles[] = $roles;
    }

    /**
     * @return mixed
     */
    public function getDerniereConnexion()
    {
        return $this->derniereConnexion;
    }

    /**
     * @param mixed $timestamp
     */
    public function setDerniereConnexion($timestamp = 'now'): void
    {
        date_default_timezone_set('Europe/Paris');
        $this->derniereConnexion = new \DateTime($timestamp);
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     */
    public function setArticles($articles): void
    {
        $this->articles = $articles;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getResetPasswordTokens()
    {
        return $this->resetPasswordTokens;
    }

    /**
     * @param mixed $resetPasswordTokens
     */
    public function setResetPasswordTokens($resetPasswordTokens): void
    {
        $this->resetPasswordTokens = $resetPasswordTokens;
    }

    /**
     * @return mixed
     */
    public function getActiveAccountToken()
    {
        return $this->activeAccountToken;
    }

    /**
     * @param mixed $activeAccountToken
     */
    public function setActiveAccountToken($activeAccountToken): void
    {
        $this->activeAccountToken = $activeAccountToken;
    }


    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    // Données sensibles
    public function eraseCredentials()
    {
        return null;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

}
