<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"product_read", "product_details_read"}},
 *     denormalizationContext={"groups"={"product_write"}},
 *     collectionOperations={
 *          "get"={},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"},
 *       },
 *      itemOperations={
 *          "get"={}
 *      }    
 * )
 * @ApiFilter(SearchFilter::class, properties={"brand": "partial"})
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"}) 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide")
     * @Assert\Length(min="2", minMessage="Ce champ doit contenir un minimum de {{ limit }} caractères")
     * @Groups({"product_read", "product_details_read", "product_write"}) 
     */
    private $brand;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le champ ne doit pas être vide")
     * @Assert\Length(min="5", minMessage="Ce champ doit contenir un minimum de {{ limit }} caractères")
     * @Groups({"product_read", "product_details_read", "product_write"}) 
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"product_read", "product_details_read", "product_write"}) 
     */
    private $price;

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
        $this->brand = $brand;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
