<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource(
 *     routePrefix="/transactions",
 *     attributes={
 *      "security"="is_granted('ROLE_AdminSysteme') or is_granted('ROLE_AdminAgence') or is_granted('ROLE_UserAgence')",
 *      "security_message"="Vous n'avez pas acces à ce ressource",
 *     "pagination_items_per_page"=10
 * },
 *     collectionOperations={
 *     "get"={"path"="", "normalization_context"={"groups"={"transaction:read"}}},
 *     "user"={"path"="/currentuser"},
 *     "post"={"path"=""},
 *     "agence"={"method"="GET", "path"="/agence/parts", "normalization_context"={"groups"={"parts:read"}}},
 *     "transaction"={"method"="GET", "path"="/mestransactions", "normalization_context"={"groups"={"trnt:read"}}},
 *     },
 *      itemOperations={
 *     "get"={"path"="/{id}"},
 *     "code"={"method"="GET", "path"="/code"},
 *     "put"={"path"="/{id}"},
 *     "delete"={"path"="/{id}"}
 *     }
 * ) 
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "transaction:write", "parts:read", "partCompte:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $date_depot;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $date_retrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, properties={"code_transfert":"exact"})
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $code_transfert;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $frais;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "transaction:write"})
     * @Assert\NotBlank(message="Le montant est obligatoire")
     */
    private $montant;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $partEtat;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "transaction:write", "parts:read", "partCompte:read"})
     */
    private $partTransfert;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "transaction:write"})

     */
    private $partDepot;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $partRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactions", cascade={"persist"})
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Comptes::class, inversedBy="transactions", cascade={"persist"})
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions")
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $users;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, properties={"type":"exact"}) 
     * @Assert\NotBlank(message="Le type est obligatoire")
     * @Groups({"transaction:read", "transaction:write"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsCancelled = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statutTransaction = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->date_depot;
    }

    public function setDateDepot(\DateTimeInterface $date_depot): self
    {
        $this->date_depot = $date_depot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->date_retrait;
    }

    public function setDateRetrait(\DateTimeInterface $date_retrait): self
    {
        $this->date_retrait = $date_retrait;

        return $this;
    }

    public function getCodeTransfert(): ?string
    {
        return $this->code_transfert;
    }

    public function setCodeTransfert(string $code_transfert): self
    {
        $this->code_transfert = $code_transfert;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    

    public function getPartEtat(): ?int
    {
        return $this->partEtat;
    }

    public function setPartEtat(int $partEtat): self
    {
        $this->partEtat = $partEtat;

        return $this;
    }

    public function getPartTransfert(): ?int
    {
        return $this->partTransfert;
    }

    public function setPartTransfert(int $partTransfert): self
    {
        $this->partTransfert = $partTransfert;

        return $this;
    }

    public function getPartDepot(): ?int
    {
        return $this->partDepot;
    }

    public function setPartDepot(int $partDepot): self
    {
        $this->partDepot = $partDepot;

        return $this;
    }

    public function getPartRetrait(): ?int
    {
        return $this->partRetrait;
    }

    public function setPartRetrait(int $partRetrait): self
    {
        $this->partRetrait = $partRetrait;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsCancelled(): ?bool
    {
        return $this->IsCancelled;
    }

    public function setIsCancelled(bool $IsCancelled): self
    {
        $this->IsCancelled = $IsCancelled;

        return $this;
    }

    public function getStatutTransaction(): ?bool
    {
        return $this->statutTransaction;
    }

    public function setStatutTransaction(bool $statutTransaction): self
    {
        $this->statutTransaction = $statutTransaction;

        return $this;
    }
}
