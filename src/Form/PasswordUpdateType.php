<?php

namespace App\Form;


use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfig("Mot de passe actuel", "Entrez votre ancien mot de passe"))
            ->add('newPassword', PasswordType::class, $this->getConfig("Nouveau mot de passe", "Entrez votre nouveau mot de passe"))
            ->add('confirmPassword', PasswordType::class, $this->getConfig("Confirmation du nouveau mot de passe", "Confirmez votre nouveau mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
