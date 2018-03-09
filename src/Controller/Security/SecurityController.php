<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 05/03/2018
 * Time: 10:20
 */

namespace App\Controller\Security;


use App\Entity\Auteur;
use App\Entity\TokenAuteur;
use App\Form\Type\AuteurType;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\ResetPasswordType;
use App\Service\Security\MyMailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class SecurityController extends Controller
{

    /**
     * Inscription d'un utilisateur
     * @Route("/register", name="security_register", methods={"GET","POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register (Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $tokenGenerator,\Swift_Mailer $mailer)
    {
        $auteur = new Auteur();
        $auteur->setRoles('ROLE_MEMBRE');

        $form = $this->createForm(AuteurType::class,$auteur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On encode/chiffre le mot de passe avec l'encoders voulu
            $password = $passwordEncoder->encodePassword($auteur,$auteur->getPassword());
            // On ré-affecte le nouveau mot de passe de l'auteur
            $auteur->setPassword($password);

            $token = new TokenAuteur();
            $token->setAuteur($auteur);
            $token->setToken($tokenGenerator->generateToken());

            $auteur->setActiveAccountToken($token);

            // Notre entity manager
            $em = $this->getDoctrine()->getManager();
            $em->persist($token);
            $em->persist($auteur);
            $em->flush();
            return $this->redirect('connexion?inscription=success');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Connexion d'un utilisateur
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/connexion", name="security_connexion")
     */
    public function connexion(AuthenticationUtils $authenticationUtils )
    {
        // On récupère le messagte d'erreur si il y en a un
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastEmail = $authenticationUtils->getLastUsername();

        return $this->render('security/connexion.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error
        ]);
    }

    /**
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @param MyMailer $myMailer
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/forgot-password", name="security_forgot_password")
     */
    public function forgotPassword(Request $request, TokenGeneratorInterface $tokenGenerator, MyMailer $myMailer)
    {
        // Creation d'un form a partir d'un nouvel auteur
        // Voir ici car c'est moche la recup
        $auteur = $this->getDoctrine()->getRepository(Auteur::class)->findOneBy(['email' => $request->request->get('reset_password')]);
        $form = $this->createForm(ResetPasswordType::class, $auteur);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var Auteur $auteur */
            $auteur = $form->getData();
            $token = new TokenAuteur();
            $token->setAuteur($auteur);
            $token->setToken($tokenGenerator->generateToken());

            $em = $this->getDoctrine()->getManager();
            $em->persist($token);
            $em->flush();
            // Envoie un email de forgot password
            $myMailer->forgotPasswordMailer($auteur->getEmail(), $token->getToken());

        }

        return $this->render('security/forgot.html.twig',[
            'form' => $form->createView(),
            'token' => $tokenGenerator->generateToken()
        ]);
    }

    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/reset-password/{token}", name="security_reset_password")
     */
    public function resetPassword(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        /** @var TokenAuteur $token */
        $token = $this->getDoctrine()->getRepository(TokenAuteur::class)->findOneByToken($request->get('token'));
        $now = new \DateTime('now');

        if($token == null || $now > $token->getDateValidite()) {

            return $this->render('security/change-password.html.twig',[
                'form' => null,
                'invalid_token' => true
            ]);

        }else{

            $form = $this->createForm(ChangePasswordType::class,$token->getAuteur());

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $auteur = $form->getData();
                // On encode/chiffre le mot de passe avec l'encoders voulu
                $password = $passwordEncoder->encodePassword($auteur,$auteur->getPassword());
                // On ré-affecte le nouveau mot de passe de l'auteur
                $auteur->setPassword($password);
                /** @var Auteur $auteur */
                $em = $this->getDoctrine()->getManager();
                $em->persist($auteur);
                $em->flush();

                // add flash messages
                $flashbag = $this->get('session')->getFlashBag();
                $flashbag->add(
                    'success',
                    'Votre mot de passe à bien été mis à jour !'
                );
                return $this->redirectToRoute('index');
            }
            return $this->render('security/change-password.html.twig',[
                'form' => $form->createView(),
                'invalid_token' => false
            ]);
        }
    }


    /**
     * @Route("/validation-account/{token}", name="security_validation_register")
     */
    public function validationRegister(Request $request)
    {
        /** @var TokenAuteur $token */
        $token = $this->getDoctrine()->getRepository(TokenAuteur::class)->findOneByToken($request->get('token'));
        $now = new \DateTime('now');

        if($token == null || $now > $token->getDateValidite()) {

            $flashbag = $this->get('session')->getFlashBag();
            $flashbag->add(
                'error',
                'Votre délai d\'activation est dépassé !'
            );
        }else{
            /** @var Auteur $auteur */
            $auteur = $token->getAuteur();
            $auteur->setIsActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            $em->flush();

            $flashbag = $this->get('session')->getFlashBag();
            $flashbag->add(
                'success',
                'Votre compte est maintenant actif !'
            );
        }
        return $this->redirectToRoute('index');
    }
}