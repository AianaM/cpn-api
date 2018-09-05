<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"realty"}},
 *     denormalizationContext={"groups"={"realty"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RealtyRepository")
 */
class Realty
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"realty", "address"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"realty","address"})
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"realty","address"})
     */
    private $area;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"realty","address"})
     */
    private $price;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @Groups({"realty"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"realty"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @Groups({"realty"})
     */
    private $manager;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MediaObject", inversedBy="realties")
     * @Groups({"realty"})
     */
    private $mediaObjects;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"realty"})
     */
    private $cadastralNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"realty"})
     */
    private $fee;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     * @Groups({"realty"})
     */
    private $exclusive = false;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     * @Groups({"realty"})
     */
    private $hiddenInfo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"realty"})
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"realty"})
     */
    private $floor;

    /**
     *
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", inversedBy="realty", cascade={"persist"})
     * @ORM\JoinColumn(name="realty", nullable=false, referencedColumnName="id")
     * @Groups({"realty"})
     */
    private $address;

    /**
     * @ORM\Column(type="json_array")
     * @Groups({"realty"})
     */
    private $owner;

    public function __construct()
    {
        $this->mediaObjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(?int $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
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
        }

        return $this;
    }

    public function removeMediaObject(MediaObject $mediaObject): self
    {
        if ($this->mediaObjects->contains($mediaObject)) {
            $this->mediaObjects->removeElement($mediaObject);
        }

        return $this;
    }

    public function getCadastralNumber(): ?string
    {
        return $this->cadastralNumber;
    }

    public function setCadastralNumber(?string $cadastralNumber): self
    {
        $this->cadastralNumber = $cadastralNumber;

        return $this;
    }

    public function getFee(): ?int
    {
        return $this->fee;
    }

    public function setFee(?int $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function getExclusive(): ?bool
    {
        return $this->exclusive;
    }

    public function setExclusive(bool $exclusive): self
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    public function getHiddenInfo()
    {
        return $this->hiddenInfo;
    }

    public function setHiddenInfo($hiddenInfo): self
    {
        $this->hiddenInfo = $hiddenInfo;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(?int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(?int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner): self
    {
        $this->owner = $owner;

        return $this;
    }

}
