<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, $this->getConfig('Prénom', null))
        ->add('lastname', TextType::class, $this->getConfig('Nom', null))
        ->add('email', EmailType::class,  $this->getConfig('Email', null))
        ->add('address', TextType::class, $this->getConfig('Adresse postale', null))
        ->add('postalCode', NumberType::class,['invalid_message' => 'Veuillez entrez un code postal en chiffres. Ex: 31300, 66000 ...'], $this->getConfig('Code postal', null))
        ->add('city', TextType::class, $this->getConfig('Ville', null))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
