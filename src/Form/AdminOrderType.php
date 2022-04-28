<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Order;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminOrderType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class, ['disabled'=>'true', 'label'=>'Référence'])
            ->add('created_at', DateTimeType::class, ['disabled'=>true, 'widget' => 'single_text', 'label'=> 'Commande créée le'])
            ->add('updated_at', DateTimeType::class, ['disabled'=>true, 'widget' => 'single_text','label'=> 'Commande modifiée le'])
            ->add('deliveryAddress', TextareaType::class, $this->getConfig('Adresse de livraison', ''))
            ->add('price', NumberType::class, ['disabled'=>true, 'label'=> 'Montant réglé par le client (en euros)'])
            ->add('user', EntityType::class, [
                'class'=> User::class,
                'choice_label' => function ($user) {
                    return $user->getFullName()." - ID: ".$user->getId();},
                'label'=> 'Client'])
            ->add('menu', EntityType::class, [
                'class'=> Menu::class,
                'choice_label' => function ($menu) {
                    return $menu->getTitle();},
                'label'=> 'Menu choisi'])
            ->add('deliveredStatus', CheckboxType::class, ['label'=> "Cochez si la commande est livrée.", "required"=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
