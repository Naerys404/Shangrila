<?php

namespace App\Form;

use App\Entity\Menu;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminMenuType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,['required'=>true], $this->getConfig('Nom du menu', ''))
            ->add('price', NumberType::class, ['required'=>true, 'invalid_message'=>'Veuillez entrer un nombre ou un chiffre.'], $this->getConfig('Prix du menu', '') )
            ->add('description', TextareaType::class,  $this->getConfig('Description', ''))
            ->add('image', UrlType::class,  $this->getConfig('Image', ''))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
