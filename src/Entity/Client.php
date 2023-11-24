<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * 
 * @ApiResource(
 *     normalizationContext={"groups"={"client_read", "client_details_read"}},
 *     denormalizationContext={"groups"={"client_write"}},
 *     collectionOperations={
 *          "get"={},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"},
 *       },
 *      itemOperations={
 *          "get"={},
 *          "put"={"access_control"="is_granted('ROLE_USER') and object.owner == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN')"},
 *      },
 *      subresourceOperations={
 *        "users_get_subresource"={
 *          "openapi_context"={
 *            "summary"="users client list "
 *          }
 *        }
 *      },  
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 * @ApiFilter(ExistsFilter::class, properties={"users"})
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ ne doit pas être vide | ")
     * @Assert\Length(min="2", minMessage="Ce champ doit contenir un minimum de {{ limit }} caractères")
     * @Groups({"client_read", "user_details_read", "client_details_read", "client_write"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client")
     * @Groups({"client_read"})
     * @ApiSubresource
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }
}
