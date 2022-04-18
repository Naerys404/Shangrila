<?php

namespace App\Form;

use App\Entity\Meal;
use App\Entity\Menu;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminMealType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, $this->getConfig('Titre', null))
            ->add('description', TextareaType::class, $this->getConfig('Description', null))
            ->add('image', UrlType::class, $this->getConfig('Image', null))
            ->add('category', EntityType::class, [
                'class'=> Menu::class,
                'choice_label' => function ($category) {
                    return $category->getTitle();},
                'label'=> 'Categorie'

            ]);
        
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
        ]);
    }
}
