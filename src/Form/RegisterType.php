<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, $this->getConfig('Prénom', 'Votre prénom'))
            ->add('lastname', TextType::class, $this->getConfig('Nom', 'Votre nom'))
            ->add('email', EmailType::class,  $this->getConfig('Email', 'Renseignez un email valide'))
            ->add('password', PasswordType::class,  $this->getConfig('Mot de passe', 'Choisissez un mot de passe'))
            ->add('confirmPassword', PasswordType::class,  $this->getConfig('Confirmation du mot de passe', 'Confirmez votre mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
