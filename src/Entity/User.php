<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list", "details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Groups({"list" , "details"})
     * @Assert\NotBlank(groups={"Create"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Groups({"list" , "details"})
     * @Assert\NotBlank(groups={"Create"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({ "details"})
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({ "details"})
     */
    private $contactNumber;

    /**
     * @ORM\Column(type="json", length=255,nullable=true )
     * @Serializer\Groups({ "list","details"})
     */
    private $roles = [];
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Serializer\Groups({ "details"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users",cascade={"persist"}, fetch="EAGER")
     * @Serializer\Groups({"list","details"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
/**
 * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
 *
 * @see PasswordAuthenticatedUserInterface
 */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getContactNumber(): ?int
    {
        return $this->contactNumber;
    }

    public function setContactNumber(int $contactNumber): self
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {

        $roles = $this->roles;

        return array_unique($roles);

    }

    public function setRole(array $role): self
    {
        $this->roles = $role;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
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

    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }
    public function getUserIdentifier()
    {
        return (string) $this->email;
    }
}
