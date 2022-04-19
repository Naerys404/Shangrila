<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', IntegerType::class, $this->getConfig('Note','Attribuez une note au restaurant, entre 0 et 5',
            ['attr'=>['min'=>0,'max'=>5]]))
            ->add('content', TextareaType::class, $this->getConfig('Commentaire', 'Donnez nous votre avis sur le restaurant et son service'))
            ->add('publicView', CheckboxType::class, ['label' => 'Voulez-vous rendre votre commentaire public ?',
            'required' => false])
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
