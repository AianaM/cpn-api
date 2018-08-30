<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(attributes={
 *   "normalization_context"={"groups"={"stream", "stream-user", "user"}}
 * })
 * @ORM\Entity(repositoryClass="App\Repository\StreamRepository")
 */
class Stream
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"stream"})
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="streams")
     * @ORM\JoinColumn(name="createdUser", referencedColumnName="id")
     * @ApiSubresource
     * @Groups("stream-user")
     */
    private $createdUser;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"stream"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"stream"})
     */
    private $action;

    /**
     * @var array
     * @Orm\Column(type="json_array", nullable=true, options={"jsonb": true})
     * @Groups({"stream"})
     */
    private $snapshot;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"stream"})
     */
    private $item;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"stream"})
     */
    private $itemId;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSnapshot()
    {
        return $this->snapshot;
    }

    public function setSnapshot($snapshot): self
    {
        $this->snapshot = $snapshot;

        return $this;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }
}
