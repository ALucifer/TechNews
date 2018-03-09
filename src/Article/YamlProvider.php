<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 27/02/2018
 * Time: 15:52
 */

namespace App\Article;

use Symfony\Component\HttpKernel\KernelInterface;

class YamlProvider
{

    private $kernel;

    /**
     * ArticleProvider constructor.
     * @param $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }


    /**
     * @return array Tableau de données
     */
    public function getArticles()
    {
       return unserialize(
           file_get_contents($this->kernel->getCacheDir() . '/yaml-articles.php'
       ));
    }
}