<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constrains as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(collectionOperations: ["get", "post"], itemOperations: ["get", "patch", "delete"], normalizationContext: ['groups' => ['message:read']], denormalizationContext: ['groups' => ['message:write']])]

class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["message:read", "comment:read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["message:read", "message:write"])]
    private $image;

    #[ORM\Column(type: 'text')]
    #[Groups(["message:read", "message:write"])]
    private $content;

 
    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Comment::class)]
    #[Groups("message:read")]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Media::class)]
    private $media;

    #[ORM\Column(type: 'integer')]
    #[Groups(["message:read", "message:write"])]
    private $count_like;

    #[ORM\Column(type: 'datetime')]
    #[Groups("message:read")]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups("message:read")]
    private $updated_at;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["message:read", "message:write"])]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
#[Assert\NotBlanc]
    #[Groups(["message:read", "message:write"])]
    private $title;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Liked::class)]
    private $likeds;

    public function __construct()
    {
        
        $this->comments = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->created_at = new DateTime();
        $this->count_like = 0;
        $this->likeds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMessage($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless almessage:ready changed)
            if ($comment->getMessage() === $this) {
                $comment->setMessage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setMessage($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless almessage:ready changed)
            if ($medium->getMessage() === $this) {
                $medium->setMessage(null);
            }
        }

        return $this;
    }

    public function getCountLike(): ?int
    {
        return $this->count_like;
    }

    public function setCountLike(int $count_like): self
    {
        $this->count_like = $count_like;

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

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Liked>
     */
    public function getLikeds(): Collection
    {
        return $this->likeds;
    }

    public function addLiked(Liked $liked): self
    {
        if (!$this->likeds->contains($liked)) {
            $this->likeds[] = $liked;
            $liked->setMessage($this);
        }

        return $this;
    }

    public function removeLiked(Liked $liked): self
    {
        if ($this->likeds->removeElement($liked)) {
            // set the owning side to null (unless already changed)
            if ($liked->getMessage() === $this) {
                $liked->setMessage(null);
            }
        }

        return $this;
    }
}