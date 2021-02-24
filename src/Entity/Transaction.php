<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource()
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_depot;

    /**
     * @ORM\Column(type="date")
     */
    private $date_retrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_transfert;

    /**
     * @ORM\Column(type="integer")
     */
    private $frais;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

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
}
