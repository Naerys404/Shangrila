<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[HasLifecycleCallbacks()]
class Order
{
    const DEVISE = 'eur';
    
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'orders')]
    private $menu;

    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'text')]
    private $deliveryAddress;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $stripe_token;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $brand_stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $last4_stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $id_charge_stripe;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $status_stripe;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\PrePersist]
    public function prePersist(){
          if(empty($this->createdAt)){
                $this->createdAt = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
           }
           if(empty($this->reference)){
               $this->reference = uniqid();
           }
    }

    public function __construct()
    {
        $this->menu = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * @return Collection<int, Menu>
     */
    public function getMenu()
    {
        return $this->menu;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
        }

        return $this;
    }


    public function removeMenu(Menu $menu): self
    {
        $this->menu->removeElement($menu);

        return $this;
    }

    
    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }


    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }


    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getPriceId()
    {
        return $this->price;
    }

    public function setPriceId($priceId)
    {
        $this->price = $priceId;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripe_token;
    }

    public function setStripeToken(?string $stripe_token): self
    {
        $this->stripe_token = $stripe_token;

        return $this;
    }

    public function getBrandStripe(): ?string
    {
        return $this->brand_stripe;
    }

    public function setBrandStripe(?string $brand_stripe): self
    {
        $this->brand_stripe = $brand_stripe;

        return $this;
    }

    public function getLast4Stripe(): ?string
    {
        return $this->last4_stripe;
    }

    public function setLast4Stripe(?string $last4_stripe): self
    {
        $this->last4_stripe = $last4_stripe;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->id_charge_stripe;
    }

    public function setIdChargeStripe(?string $id_charge_stripe): self
    {
        $this->id_charge_stripe = $id_charge_stripe;

        return $this;
    }

    public function getStatusStripe(): ?string
    {
        return $this->status_stripe;
    }

    public function setStatusStripe(?string $status_stripe): self
    {
        $this->status_stripe = $status_stripe;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }


}
