<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     collectionOperations={"get", "byLastName"}
 * )
 * @ApiFilter(SearchFilter::class, properties={"email": "exact", "name"="partial"})
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     * @Groups({"read"})
     */
    private $roles;

    /**
     * @var array
     * @Orm\Column(type="json_array", nullable=true, options={"jsonb": true})
     * @Groups({"read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MediaObject", mappedBy="createdUser")
     */
    private $mediaObjects;

    public function __construct()
    {
        $this->mediaObjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getName(): array
    {
        return $this->name;
    }

    public function setName(array $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|MediaObject[]
     */
    public function getMediaObjects(): Collection
    {
        return $this->mediaObjects;
    }

    public function addMediaObject(MediaObject $mediaObject): self
    {
        if (!$this->mediaObjects->contains($mediaObject)) {
            $this->mediaObjects[] = $mediaObject;
            $mediaObject->setCreatedUser($this);
        }

        return $this;
    }

    public function removeMediaObject(MediaObject $mediaObject): self
    {
        if ($this->mediaObjects->contains($mediaObject)) {
            $this->mediaObjects->removeElement($mediaObject);
            // set the owning side to null (unless already changed)
            if ($mediaObject->getCreatedUser() === $this) {
                $mediaObject->setCreatedUser(null);
            }
        }

        return $this;
    }

}
