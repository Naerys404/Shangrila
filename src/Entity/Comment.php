<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\CommentRepository;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks()]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[Assert\Range(min:0, max:5, notInRangeMessage:"Veuillez donner une note entre 0 et 5")]
    #[ORM\Column(type: 'integer')]
    private $rating;

    #[Assert\Length(max:500, maxMessage:"Votre commentaire ne doit pas excéder 500 caractères.")]
    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\OneToOne(inversedBy: 'comment', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $author;

    //création d'une date automatiquement lors de la création du commentaire
    #[ORM\PrePersist]
    public function prePersist(){
          if(empty($this->createdAt)){
                $this->createdAt = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
          } 
    }
    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
