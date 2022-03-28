<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, $this->getConfig("Prénom", "Votre prénom"))
            ->add('lastname', TextType::class, $this->getConfig("Nom", "Votre 
            nom"))
            ->add('email', EmailType::class, $this->getConfig("Email", "Adresse mail") )
            ->add('address', TextType::class, $this->getConfig("Adresse de livraison", "Numéro et nom de la voie"))
            ->add('postalCode', NumberType::class, $this->getConfig("Code postal", "Code postal du lieu de livraison"))
            ->add('city', TextType::class, $this->getConfig("Ville", "Ville du lieu de livraison"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
