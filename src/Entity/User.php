<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(
    fields:"email",
    message:"Un autre utilisateur s'est déjà inscrit avec cette adresse mail."
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email(message:"Veuillez renseigner un mail valide.")]
    private $email;

    #[Assert\Length(min:10, max:300, minMessage:"Votre adresse doit comporter au moins 10 caractères.", maxMessage:"Votre adresse doit comporter maximum 300 caractères.")]
    #[ORM\Column(type: 'text', nullable: true)]
    private $address;

    #[Assert\Length(min:8, minMessage:'Votre mot de passe doit comporter au moins 8 caractères.')]
    #[ORM\Column(type: 'string', length: 255)]
    private $hash;

    #[Assert\EqualTo(propertyPath:"password", message:"Les deux mots de passe ne correspondent pas.")]
    public $confirmPassword;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    //limitation des code postaux français
    #[Assert\Range(min:01000, max:95850, notInRangeMessage:"Code postal invalide.")]
    #[ORM\Column(type: 'integer', nullable: true)]
    private $postalCode;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;

    #[ORM\OneToMany(mappedBy: 'booker', targetEntity: TableBooking::class)]
    private $tableBookings;

    #[ORM\OneToOne(mappedBy: 'author', targetEntity: Comment::class, cascade: ['persist', 'remove'])]
    private $comment;

    public function __construct()
    {
        $this->tableBookings = new ArrayCollection();
    }

    //date de création automatisée lors de l'inscription
    #[ORM\PrePersist]
    public function prePersist(){
          if(empty($this->createdAt)){
                $this->createdAt = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
          } 
    }

    public function getFullname(){
        return "{$this->firstname} {$this->lastname}";
    }

    public function getFulladdress(){
        return "{$this->address}<br>{$this->postalCode}<br>{$this->city}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    
    public function setRoles(array $roles): self {
        $this->roles = $roles;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }
    
    //requis par PasswordAuthenticatedUserInterface
    public function getPassword(): string
        {
            return $this->hash;
        }
    
        public function setPassword(string $hash): self
        {
              $this->hash = $hash;
              return $this;
        }

    //requis par UserInterface
    public function getSalt(){
        return null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    public function eraseCredentials(){}



    /**
     * @return Collection<int, TableBooking>
     */
    public function getTableBookings(): Collection
    {
        return $this->tableBookings;
    }

    public function addTableBooking(TableBooking $tableBooking): self
    {
        if (!$this->tableBookings->contains($tableBooking)) {
            $this->tableBookings[] = $tableBooking;
            $tableBooking->setBooker($this);
        }

        return $this;
    }

    public function removeTableBooking(TableBooking $tableBooking): self
    {
        if ($this->tableBookings->removeElement($tableBooking)) {
            // set the owning side to null (unless already changed)
            if ($tableBooking->getBooker() === $this) {
                $tableBooking->setBooker(null);
            }
        }

        return $this;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        // unset the owning side of the relation if necessary
        if ($comment === null && $this->comment !== null) {
            $this->comment->setAuthor(null);
        }

        // set the owning side of the relation if necessary
        if ($comment !== null && $comment->getAuthor() !== $this) {
            $comment->setAuthor($this);
        }

        $this->comment = $comment;

        return $this;
    }
    
}
