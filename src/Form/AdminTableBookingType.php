<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\TableBooking;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminTableBookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateType::class,  [
            'widget' => 'single_text'
        ])
        ->add('timeSheet', ChoiceType::class, [
            'required'=>true,
            'choices'=> ['12:00'=>'12h00',
            '19:00'=>'19h00',
            '20:00'=>'20h00',
            '21:00'=>'21h00']
            ,
            'label'=>"Heure de la réservation"
        ])
        ->add('guests', ChoiceType::class, [
            'required'=>true,
            'label'=>"Nombre de convives",
            'choices'=> [
                '1 invité'=>1,
                '2 invités'=>2,
                '3 invités'=>3,
                '4 invités'=>4
            ],
            ])
            ->add('booker', EntityType::class, [
                'class'=> User::class,
                'choice_label' => function ($booker) {
                    return $booker->getFullname();},
                'label'=> 'Client'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableBooking::class,
        ]);
    }
}
