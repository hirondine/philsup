<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(collectionOperations:["get","post"],itemOperations:["put","delete"])]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string',)]
    private $image;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Like::class)]
    private $likes;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Comment::class)]
    private $comments;

    #[ORM\Column(type: 'integer')]
    private $count_like;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Media::class)]
    private $media;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Media::class)]
public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->created_at = new DateTime();
        $this->count_like = 0;
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
            // set the owning side to null (unless already changed)
            if ($medium->getMessage() === $this) {
                $medium->setMessage(null);
            }
        }

        return $this;
    }
}
