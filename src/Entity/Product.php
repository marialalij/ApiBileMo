<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use JMS\Serializer\Annotation\Groups as Groups;
use JMS\Serializer\Annotation\Exclude;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;


/**
 * @Serializer\XmlRoot("Product")
 * @Hateoas\Relation("self", href = "expr('/api/products/' ~ object.getId())")
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource
 * @ApiFilter(searchFilter::class)
 * @ApiFilter(OrderFilter::class)
 * 
 */
class Product
{
    /**
     * @Serializer\XmlAttribute
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @OA\Property(description="l'identifiant est unique", type="integer")
     *
     * @Exclude
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product:read", "product:detail"})
     *
     * @OA\Property(description="brand du produit", type="string")
     */
    private $brand;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Groups({"product:read", "product:detail"})
     *
     * @OA\Property(description="prix du produit", type="string")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:read", "product:detail"})
     *
     * @OA\Property(description="modele du produit", type="string")
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:read", "product:detail"})
     *
     * @OA\Property(description="Coleur du produit", type="string")
     */
    private $color;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->name = $brand;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
