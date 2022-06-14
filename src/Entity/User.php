<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Serializer\Groups({"list"})
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
     * @ORM\Column(type="string", length=255,nullable=true )
     * @Serializer\Groups({ "details"})
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users",cascade={"all"}, fetch="EAGER")
     * @Serializer\Groups({"list","details"})
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({ "details"})
     */
    private $email;

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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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
}
