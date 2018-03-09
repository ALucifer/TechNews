<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 01/03/2018
 * Time: 12:56
 */

namespace App\Service\Twig;

use App\Controller\Helper;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    use Helper;

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getFilters()
    {
        return [
            new \Twig_Filter('slugify', [$this, 'slugifyFilter']),
            new \Twig_Filter('accroche', [$this,'accrocheFilter'])
        ];
    }

    public function accrocheFilter($text)
    {
        $string = strip_tags($text);

        if (strlen($string) > 170){
            $stringCut = substr($string,0,170);
            $string  = substr($stringCut,0, strrpos($stringCut, ' '));
        }

        return $string . '...';
    }

    public function slugifyFilter($text)
    {
       return $this->slugify($text);
    }

    public function getFunctions ()
    {
        return [
            new \Twig_Function('isUserInvited', function () {
                return $this->session->get('inviteUser');
            })
        ];
    }
}