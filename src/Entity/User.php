<?php

namespace App\Entity;

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
 *     collectionOperations={"get",
 *     "post"={"denormalization_context"={"groups"={"createUser"}}},
 *     "byLastName"={"denormalization_context"={"groups"={"lastName"}}},
 *     "authState"={"pagination_enabled"=false,"filters"={}, "_api_receive"=false}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"email": "exact", "roles": "partial"})
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
     * @Groups({"read", "user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"read", "createUser"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     * @Groups({"createUser"})
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
     * @Groups({"read", "lastName", "createUser", "user"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject", inversedBy="users")
     * @Groups({"read"})
     */
    private $photo;

    /**
     * @Groups({"read"})
     */
    private $teamCard;

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
        array_push($roles, "ROLE_USER");
        $this->roles = array_unique($roles);

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
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
    }

    public function getPhoto(): ?MediaObject
    {
        return $this->photo;
    }

    public function setPhoto(?MediaObject $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getTeamCard()
    {
        if(array_key_exists('teamCard', $this->name)){
            $this->teamCard = $this->name['teamCard'];
        }
        return $this->teamCard;
    }

    public function setTeamCard(array $teamCard): self
    {
        $this->name['teamCard'] = $teamCard;

        return $this;
    }

}
