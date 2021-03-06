<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminSystemeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass=AdminSystemeRepository::class)
* @ApiResource(
 * 
 *     normalizationContext={"groups"={"systeme:read"}},
 *     routePrefix="/admin/systemes",
 *     attributes={
 *      "security"="is_granted('ROLE_AdminSysteme')",
 *      "security_message"="Vous n'avez pas acces à ce ressource",
 *      "pagination_items_per_page"=10
 * },
 *     collectionOperations={
 *     "get"={"path"=""},
 *     "post"={"path"=""}
 *     },
 *      itemOperations={
 *     "get"={"path"="/{id}"},
 *     "put"={"path"="/{id1}"},
 *     "delete"={"path"="/{id}"}
 *     }
 * )  */
class AdminSysteme extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
