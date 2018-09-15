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
use App\Filter\AndPartialFilter;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user"}},
 *     denormalizationContext={"groups"={"user:input"}},
 *     collectionOperations={
 *     "get"={"access_control"="is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')", "access_control_message"="У вас не достаточно прав"},
 *     "post"={"denormalization_context"={"groups"={"createUser"}}},
 *     "byLastName"={"denormalization_context"={"groups"={"lastName"}}},
 *     "authState"={"pagination_enabled"=false,"filters"={}, "_api_receive"=false},
 *     "team"={"normalization_context"={"groups"={"team"}}}
 *     },
 *     itemOperations={
 *         "get"={"access_control"="(is_granted('ROLE_USER') and object == user) or is_granted('ROLE_MANAGER') or is_granted('ROLE_ADMIN')", "access_control_message"="У вас не достаточно прав"},
 *         "put"={"access_control"="is_granted('ROLE_USER') and object == user", "access_control_message"="У вас не достаточно прав"},
 *         "saveRoles"={"denormalization_context"={"groups"={"userRoles"}}}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"email": "exact"})
 * @ApiFilter(AndPartialFilter::class, properties={"roles": "partial"})
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
     * @Groups({"user", "userRoles", "realty:input", "realty:output"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"user", "createUser", "user:input"})
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
     * @Groups({"user", "userRoles"})
     */
    private $roles;

    /**
     * @var array
     * @Orm\Column(type="json_array", nullable=true, options={"jsonb": true})
     * @Groups({"user", "lastName", "createUser", "user:input"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MediaObject", inversedBy="users")
     * @Groups({"user", "user:input"})
     */
    private $photo;

    /**
     * @Groups({"user", "userRoles", "team", "realty:output"})
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
//        $this->name = $name;
        if (array_key_exists('lastName', $name)) {
            $this->name['lastName'] = $name['lastName'];
        }

        if (array_key_exists('firstName', $name)) {
            $this->name['firstName'] = $name['firstName'];
        }

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
        if (array_key_exists('teamCard', $this->name)) {
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
