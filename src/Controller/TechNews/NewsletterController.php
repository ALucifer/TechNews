<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 06/03/2018
 * Time: 12:02
 */

namespace App\Controller\TechNews;


use App\Form\Type\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsletterController extends Controller
{
    public function newsletter ()
    {
        $form = $this->createForm(NewsletterType::class);

        return $this->render('newsletter/subscribe.html.twig', [
            'form' => $form->createView()
        ]);
    }

}