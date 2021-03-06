<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use App\Filter\SearchJsonbFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"realty:output"}},
 *     denormalizationContext={"groups"={"realty:input"}},
 *     attributes={"order"={"updatedAt": "DESC"}, "pagination_client_items_per_page"=true,
 *     "access_control_message"="Только менеджеры могут создавать и изменять объявления"},
 *     collectionOperations={
 *          "post"={"access_control"="is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')"},
 *          "get"
 *     },
 *     itemOperations={
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')"},
 *          "get"
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RealtyRepository")
 * @ApiFilter(SearchFilter::class, properties={"rooms": "exact", "category": "exact",
 *     "address.district": "exact", "address.street": "exact", "address.number": "exact",
 *     "address.floors": "exact", "address.developer": "exact", "address.type": "exact",
 *     "manager.id": "exact"
 * })
 * @ApiFilter(BooleanFilter::class, properties={"address.newBuilding"})
 * @ApiFilter(RangeFilter::class, properties={"price", "area", "address.year"})
 * @ApiFilter(SearchJsonbFilter::class, properties={"owner": "partial", "description": "partial", "address.description": "partial"})
 */
class Realty
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"realty:input", "realty:output", "address", "media"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Groups({"realty:input", "realty:output", "address", "media"})
     */
    private $category;

    /**
     * @Assert\Range(min=0, max=99999)
     *
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     * @Groups({"realty:input", "realty:output", "address"})
     */
    private $area;

    /**
     * @Assert\Range(min=0, max=9999999999)
     *
     * @ORM\Column(type="decimal", precision=12, scale=2, nullable=true)
     * @Groups({"realty:input", "realty:output", "address"})
     */
    private $price;

    /**
     * @ORM\Column(type="json_array", nullable=true, options={"jsonb": true})
     * @Groups({"realty:input", "realty:output"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"realty:input", "address", "realty:output"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @Groups({"realty:input", "realty:output"})
     */
    private $manager;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MediaObject", inversedBy="realties")
     * @Groups({"realty:input", "realty:output"})
     */
    private $mediaObjects;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"realty:input", "adminOrManager:output"})
     */
    private $cadastralNumber;

    /**
     * @Assert\Range(min=0, max=999999)
     *
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     * @Groups({"realty:input", "adminOrManager:output"})
     */
    private $fee;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     * @Groups({"realty:input", "adminOrManager:output"})
     */
    private $exclusive = false;

    /**
     * @ORM\Column(type="json_array", nullable=true, options={"jsonb": true})
     * @Groups({"realty:input", "adminOrManager:output"})
     */
    private $hiddenInfo;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"realty:input", "address", "realty:output"})
     */
    private $rooms;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"realty:input", "address", "realty:output"})
     */
    private $floor;

    /**
     *
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Address", inversedBy="realty", cascade={"persist"})
     * @ORM\JoinColumn(name="realty", nullable=false, referencedColumnName="id")
     * @Groups({"realty:input", "realty:output", "media"})
     */
    private $address;

    /**
     * @ORM\Column(type="json_array", options={"jsonb": true})
     * @Groups({"realty:input", "adminOrManager:output"})
     */
    private $owner;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"realty:input", "realty:output"})
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"realty:output"})
     */
    private $updatedUser;

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

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(?string $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
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

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(?string $fee): self
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

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedUser()
    {
        return $this->updatedUser;
    }

    public function setUpdatedUser($updatedUser): self
    {
        $this->updatedUser = $updatedUser;

        return $this;
    }

}
