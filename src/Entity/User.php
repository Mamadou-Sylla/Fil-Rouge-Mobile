<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",  type="string")
 * @ORM\DiscriminatorMap({"AdminSysteme"="AdminSysteme", "AdminAgence" = "AdminAgence", "Caissier" = "Caissier", "user" ="User", "UserAgence"="UserAgence"})
 * @ApiResource(
 * 
 *     normalizationContext={"groups"={"admin_system:read"}},
 *     denormalizationContext={"groups"={"admin_system:write"}},
 *     routePrefix="/users",
 *     attributes={
 *      "security"="is_granted('ROLE_AdminSysteme') or is_granted('ROLE_AdminAgence')",
 *      "security_message"="Vous n'avez pas acces à ce ressource",
 *     "pagination_items_per_page"=10
 * },
 *     collectionOperations={
 *     "get"={"path"=""},
 *     "user"={"method"="GET", "path"="/currentuser"},
 *      "post"={"path"=""}
 *     },
 *      itemOperations={
 *     "get"={"path"="/{id}"},
 *     "put"={"path"="/{id}"},
 *     "delete"={"path"="/{id}"}
 *     }
 * )
 * */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin_system:read", "systeme:read", "agence:read", "caissier:read", "transaction:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"transaction:read", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:read", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:read", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:read", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:read", "admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $cni;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"transaction:read", "admin_system:read", "systeme:read", "agence:read", "caissier:read"})
     */
    private $etat = false;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Groups({"admin_system:read", "systeme:read", "agence:read", "caissier:read" ,"admin_system:write", "system:write", "agence:write", "caissier:write"})
     */
    private $profils;

    /**
     * @ORM\OneToMany(targetEntity=Comptes::class, mappedBy="user")
     */
    private $comptes;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="users")
     * @Groups({"systeme:read", "agence:read", "caissier:read"})
     * @ApiSubresource()
     */
    private $agence;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="users")
     */
    private $transactions;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->comptes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profils->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getProfils(): ?Profil
    {
        return $this->profils;
    }

    public function setProfils(?Profil $profils): self
    {
        $this->profils = $profils;

        return $this;
    }

    /**
     * @return Collection|Comptes[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Comptes $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setUser($this);
        }

        return $this;
    }

    public function removeCompte(Comptes $compte): self
    {
        if ($this->comptes->removeElement($compte)) {
            // set the owning side to null (unless already changed)
            if ($compte->getUser() === $this) {
                $compte->setUser(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setUsers($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUsers() === $this) {
                $transaction->setUsers(null);
            }
        }

        return $this;
    }

  


  
}
