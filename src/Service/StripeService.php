<?php

namespace App\Service;

use App\Entity\Menu;
use App\Entity\Order;

class StripeService {

    private $privateKey;

    public function __construct(){

        //selectionne la bonne clé suivant l'environnement
        if($_ENV['APP_ENV'] === 'dev'){
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {
            //A update avec stripe_secret_key_live pour une mise en ligne réelle
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        }
    }

    //associe le prix à une clé
    public function paymentIntent(Menu $menu){
        
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount'=>$menu->getPrice() *100,
            'currency'=> Order::DEVISE,
            'payment_method_types'=>['card']

        ]);
    }

    public function payment($amount,$currency,$description, array $stripeParam){

            \Stripe\Stripe::setApiKey($this->privateKey);
            $paymentIntent = null;

            if(isset($stripeParam['stripeIntentId'])){
                $paymentIntent = \Stripe\PaymentIntent::retrieve($stripeParam['stripeIntentId']);
            }
            
            if($stripeParam['stripeIntentStatus'] === 'succeeded'){
                //TODO

            } else {
                $paymentIntent->cancel();
            }

            return $paymentIntent;

        
    }


    public function stripe(array $stripeParam, Menu $menu){
        return $this->payment(
            $menu->getPrice()*100,
            Order::DEVISE,
            $menu->getTitle(),
            $stripeParam

        );
    }
}