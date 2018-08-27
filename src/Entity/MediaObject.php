<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaObjectAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource(iri="http://schema.org/MediaObject", collectionOperations={
 *     "get",
 *     "post"={
 *         "method"="POST",
 *         "path"="/media_objects",
 *         "controller"=CreateMediaObjectAction::class,
 *         "defaults"={"_api_receive"=false},
 *     },
 * })
 * @ORM\Entity(repositoryClass="App\Repository\MediaObjectRepository")
 * @Vich\Uploadable
 */
class MediaObject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="contentUrl", size="imageSize")
     */
    public $file;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @ApiProperty(iri="http://schema.org/contentUrl")
     */
    private $contentUrl;

    /**
     * @ORM\Column(type="integer")
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="mediaObjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdUser;

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

        return $this;
    }
}
