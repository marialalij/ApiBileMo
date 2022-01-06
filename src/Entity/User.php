<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use JMS\Serializer\Annotation\Groups as Groups;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @Serializer\XmlRoot("User")
 * @Hateoas\Relation("self", href = "expr('/api/users/' ~ object.getId())")
 * @Hateoas\Relation("delete", href = "expr('/api/users/' ~ object.getId())")
 * @Hateoas\Relation("list")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource
 * @ApiFilter(searchFilter::class)
 * @ApiFilter(OrderFilter::class)
 * 
 * @OA\Schema(title="User", description="User class")
 */
class User
{
    /**
     * @Serializer\XmlAttribute
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @OA\Property(type="integer")
     * @Exclude
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Default","user:read", "user:write"})
     *
     * @OA\Property(description="Name of the user", type="string")
     * @Assert\Length(min=3, minMessage="le nome doit avoir {{ limit }} de cararctere")
     * @Assert\NotBlank(message="champ non renseigné")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Default","user:read", "user:write"})
     *
     * @OA\Property(description="firstName of the user", type="string")
     * @Assert\Length(min=3, minMessage="firstName doit avoir {{ limit }} caractere")
     * @Assert\NotBlank(message="champ non renseigné")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Default","user:read", "user:write"})
     *
     * @OA\Property(description="LastName of the user", type="string")
     * @Assert\Length(min=4, minMessage="LastName must have {{ limit }} caracters")
     * @Assert\NotBlank(message="champ non renseigné")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Default","user:read", "user:write"})
     *
     * @OA\Property(type="string")
     * @Assert\NotBlank(message="champ non renseigné")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     *
     * @OA\Property(description="Customer of the user")
     * @Exclude
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
