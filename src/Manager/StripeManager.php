<?php

namespace App\Manager;

use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Order;

use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StripeManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(EntityManagerInterface $entityManager, StripeService $stripeService)
    {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

    public function getMenus(){
        return $this->em->getRepository(Menu::class)
        ->findAll();
    }

    public function intentSecret(Menu $menu)
    {
        $intent = $this->stripeService->paymentIntent($menu);

        return $intent['client_secret'] ?? null;
    }

    /**
     * @param array $stripeParam
     * @param Menu $menu
     * @return array|null
     */
    public function stripe(array $stripeParam, Menu $menu)
    {

        $resource = null;
        $data = $this->stripeService->stripe($stripeParam, $menu);

        if ($data) {
            $resource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['status'],
                'stripeToken' => $data['client_secret']
            ];
        }

        return $resource;
    }

    //crée une commande
    /**
     * @param array $resource
     * @param Menu $menu
     * @param User $user
     * */
    public function create_subscription(array $resource, Menu $menu, User $user)
    {
        $order = new Order();

        $order->setUser($user)
              ->setMenu($menu)
              ->setPrice($menu->getPrice())
              ->setReference(uniqid('', false))
              ->setBrandStripe($resource['stripeBrand'])
              ->setLast4Stripe($resource['stripeLast4'])
              ->setIdChargeStripe($resource['stripeId'])
              ->setStripeToken($resource['stripeToken'])
              ->setStatusStripe($resource['stripeStatus'])
              ->setDeliveryAddress($user->getFulladdress())
              ->setCreatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));

        $this->em->persist($order);
        $this->em->flush();

    }
}
