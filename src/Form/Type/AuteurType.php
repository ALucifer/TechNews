<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 05/03/2018
 * Time: 10:04
 */

namespace App\Form\Type;


use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuteurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class,[
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisissez votre Prénom'
                ]
            ])
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisissez votre Nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisissez votre Email'
                ]
            ])
            ->add('password', PasswordType::class,[
                'required' => true,
                'attr' => [
                    'placeholder' => '***********'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'M\'inscrire'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class
        ]);
    }


}