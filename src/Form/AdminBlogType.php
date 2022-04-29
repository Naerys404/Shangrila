<?php

namespace App\Form;

use App\Entity\Blog;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBlogType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, $this->getConfig('Titre','Titre de l\'article'))
            ->add('content', TextareaType::class, $this->getConfig('Contenu','Contenu de l\'article'))
            ->add('author', TextType::class, $this->getConfig('Auteur','Auteur de l\'article'))
            ->add('image', UrlType::class,$this->getConfig("Image d'illustration","Illustez l'article") )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
