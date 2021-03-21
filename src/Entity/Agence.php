<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
* @ApiResource(
 * 
 *     normalizationContext={"groups"={"admin_agence:read"}},
 *     routePrefix="/agences",
 *     attributes={
 *     "denormalization_context"={"groups"={"admin_agence:write"}},
 *      "security"="is_granted('ROLE_AdminAgence') or is_granted('ROLE_AdminSysteme')",
 *      "security_message"="Vous n'avez pas acces à ce ressource",
 *     "pagination_items_per_page"=10
 * },
 *     collectionOperations={
 *     "get"={"path"=""},
 *     "post"={"path"=""},
 *     },
 *      itemOperations={
 *     "get"={"path"="/{id}"},
 *     "put"={"path"="/{id}"},
 *     "delete"={"path"="/{id}"}
 *     }
 * ) */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin_agence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin_agence:read", "admin_agence:write", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"agence:write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin_agence:read", "admin_agence:write", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"agence:write"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin_agence:read", "admin_agence:write", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"agence:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"admin_agence:read", "admin_agence:write", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"agence:write"})
     */
    private $statut = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin_agence:read", "admin_agence:write", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"agence:write"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin_agence:read", "admin_agence:write", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"agence:write"})
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agence", cascade={"persist", "remove"})
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity=Comptes::class, cascade={"persist", "remove"})
     * @Groups({"admin_agence:read", "admin_agence:write", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"agence:write"})
     */
    private $compte;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgence() === $this) {
                $user->setAgence(null);
            }
        }

        return $this;
    }

    public function getCompte(): ?Comptes
    {
        return $this->compte;
    }

    public function setCompte(?Comptes $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

}
