<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ComptesRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;


/**
 * @ORM\Entity(repositoryClass=ComptesRepository::class)
 * @UniqueEntity(fields="numeroCompte", message="Le numéro de compte est unique.")
 * @ApiResource(
 *     routePrefix="/admin/systemes",
 *     attributes={
 *      "normalization_context"={"groups"={"compte:read"}},
 *      "denormalization_context"={"groups"={"compte:write"}},
 *      "security"="is_granted('ROLE_AdminSysteme')",
 *      "security_message"="Vous n'avez pas acces à ce ressource",
 *     "pagination_items_per_page"=10
 *      },
 *     collectionOperations={
 *         "get"={"path"="/comptes"},
 *         "post"={"path"="/comptes"},
 *         "compte"={"method"="GET", "path"="/compte/parts", "normalization_context"={"groups"={"partCompte:read"}}}
 *     },
 *     itemOperations={
 *        "get"={"path"="/comptes/{id}"},
 *        "put"={"path"="/comptes/{id}"},
 *        "delete"={"path"="/comptes/{id}"},
 *        "Operations"={"method"="PATCH", "path"="/comptes/{id}", "route_name"="operation"}
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"statut"})
 */
class Comptes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"compte:read"})
     */
    private $id;
  

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comptes")
     * @Groups({"compte:read", "compte:write"})
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"compte:read"})
     */
    private $statut = false;

    /**
     * @ORM\Column(type="string")
     * @Groups({"compte:read", "compte:write", "depot:write"})
     */
    private $numeroCompte;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"compte:read", "compte:write"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"compte:read", "compte:write", "depot:write"})
     * @Assert\NotBlank(message="Le solde est obligatoire")
     * @Assert\GreaterThanOrEqual(value = 700000, message="Le solde doit superieur a 700000 lors du creation")
     */
    private $solde;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compte")
     * @Groups({"partCompte:read"})
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(string $numeroCompte): self
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

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
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

  
}
