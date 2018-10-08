<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"address"}},
 *     denormalizationContext={"groups"={"address"}},
 *     attributes={"access_control_message"="Только менеджеры могут создавать и изменять объявления"},
 *     collectionOperations={
 *          "post"={"access_control"="is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')"},
 *          "get"
 *     },
 *     itemOperations={
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')"},
 *          "get"
 *     }
 * )
 * @ApiFilter(GroupFilter::class, arguments={"parameterName": "groups", "overrideDefaultGroups": false, "whitelist": {"street"}})
 * @ApiFilter(SearchFilter::class, properties={"street": "ipartial"})
 * @ApiFilter(BooleanFilter::class, properties={"newBuilding"})
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @UniqueEntity(fields={"street", "number"}, errorPath="number", message="This number is already in use on that street.")
 */
class Address
{
    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"address", "realty:input", "realty:output", "street"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @Groups({"address", "realty:input", "realty:output", "street"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $district;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $developer;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $newBuilding = false;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $floors;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\Column(type="json_array", nullable=true, options={"jsonb": true})
     */
    private $description;

    /**
     * @Groups({"address"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Realty", mappedBy="address")
     */
    private $realty;

    /**
     * @Groups({"address", "realty:input", "realty:output"})
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\MediaObject", inversedBy="addresses")
     */
    private $mediaObjects;

    public function __construct()
    {
        $this->realty = new ArrayCollection();
        $this->mediaObjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDeveloper(): ?string
    {
        return $this->developer;
    }

    public function setDeveloper(?string $developer): self
    {
        $this->developer = $developer;

        return $this;
    }

    public function getNewBuilding(): ?bool
    {
        return $this->newBuilding;
    }

    public function setNewBuilding(bool $newBuilding): self
    {
        $this->newBuilding = $newBuilding;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getFloors(): ?int
    {
        return $this->floors;
    }

    public function setFloors(?int $floors): self
    {
        $this->floors = $floors;

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

    /**
     * @return Collection|Realty[]
     */
    public function getRealty(): Collection
    {
        return $this->realty;
    }

    public function addRealty(Realty $realty): self
    {
        if (!$this->realty->contains($realty)) {
            $this->realty[] = $realty;
            $realty->setAddress($this);
        }

        return $this;
    }

    public function removeRealty(Realty $realty): self
    {
        if ($this->realty->contains($realty)) {
            $this->realty->removeElement($realty);
            // set the owning side to null (unless already changed)
            if ($realty->getAddress() === $this) {
                $realty->setAddress(null);
            }
        }

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
}
