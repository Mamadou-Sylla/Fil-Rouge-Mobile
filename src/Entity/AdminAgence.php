<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminAgenceRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=AdminAgenceRepository::class)
 * @ApiResource(
 * 
 *     normalizationContext={"groups"={"agence:read"}},
 *     denormalizationContext={"groups"={"agence:write"}},
 *     routePrefix="/admin/agence",
 *     attributes={
 *      "security"="is_granted('ROLE_AdminSysteme')",
 *      "security_message"="Vous n'avez pas acces à ce ressource",
 *      "pagination_items_per_page"=10
 * },
 *     collectionOperations={
 *     "get"={"path"=""},
 *      "post"={"path"=""}
 *     },
 *      itemOperations={
 *     "get"={"path"="/{id}"},
 *     "put"={"path"="/{id}"},
 *     "delete"={"path"="/{id}"}
 *     }
 * ) 
 * */
class AdminAgence extends User
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
