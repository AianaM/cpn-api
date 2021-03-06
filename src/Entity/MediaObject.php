<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaObjectAction;
use App\Controller\CreateMultipleMediaObjectAction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(iri="http://schema.org/MediaObject",
 *     collectionOperations={
 *          "get",
 *          "post"={"method"="POST", "path"="/media_objects", "controller"=CreateMediaObjectAction::class, "defaults"={"_api_receive"=false}, "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"},
 *          "postMultiple"={"method"="POST", "path"="/media_objects/multi", "controller"=CreateMultipleMediaObjectAction::class, "defaults"={"_api_receive"=false}, "access_control"="is_granted('IS_AUTHENTICATED_FULLY')"}
 *     },
 *     attributes={
 *          "pagination_client_items_per_page"=true,
 *          "access_control_message"="Только зарегистрированные пользователи могут загружать файлы",
 *          "normalization_context"={"groups"={"media"}},
 *          "denormalization_context"={"groups"={"media:input"}}
 *     })
 * @ApiFilter(SearchFilter::class, properties={"tags": "ipartial"})
 * @ORM\Entity(repositoryClass="App\Repository\MediaObjectRepository")
 * @Vich\Uploadable
 */
class MediaObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"media", "realty:input", "realty:output", "user", "user:input"})
     */
    private $id;

    /**
     * @var File
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="contentUrl", size="imageSize")
     * @Groups({"media:input"})
     */
    public $file;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @ApiProperty(iri="http://schema.org/contentUrl")
     * @Groups({"media", "realty:output", "user"})
     */
    private $contentUrl;

    /**
     * @var array|null
     * @Groups({"media", "realty:output", "user", "address"})
     */
    public $links;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"media"})
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"media"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"media"})
     */
    private $createdUser;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var array
     * @Assert\Type("array")
     * @Groups({"media", "realty:input", "realty:output", "media:input"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="photo")
     * @Groups({"media", "media:input"})
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Realty", mappedBy="mediaObjects")
     * @Groups({"media", "media:input"})
     */
    private $realties;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Address", mappedBy="mediaObjects")
     * @Groups({"media", "media:input"})
     */
    private $addresses;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->realties = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    public function setContentUrl(string $contentUrl): self
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImageSize(int $imageSize): self
    {
        $this->imageSize = $imageSize;

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

    public function getCreatedUser(): ?User
    {
        return $this->createdUser;
    }

    public function setCreatedUser(?User $createdUser): self
    {
        $this->createdUser = $createdUser;
        $this->setCreatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPhoto($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPhoto() === $this) {
                $user->setPhoto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Realty[]
     */
    public function getRealties(): Collection
    {
        return $this->realties;
    }

    public function addRealty(Realty $realty): self
    {
        if (!$this->realties->contains($realty)) {
            $this->realties[] = $realty;
            $realty->addMediaObject($this);
        }

        return $this;
    }

    public function removeRealty(Realty $realty): self
    {
        if ($this->realties->contains($realty)) {
            $this->realties->removeElement($realty);
            $realty->removeMediaObject($this);
        }

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->addMediaObject($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            $address->removeMediaObject($this);
        }

        return $this;
    }
}
