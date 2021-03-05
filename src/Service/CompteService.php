<?php


namespace App\Service;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Profil;
use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;



class CompteService
{
    private $serializer;
    private $validator;
    private $encoder;
    private $manager;
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, 
    UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager,
    ProfilRepository $repoProfil, UserRepository $repoUser){
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->repoProfil=$repoProfil;
        $this->repoUser=$repoUser;
    }
    /*public function Post(Request $request){

        $post_user = $request->request->all();
        $avatar=$request->files->get("avatar");
        //$post_user=json_encode($post_user); 
        $profils=$this->repoProfil->find($post_user['profils']);
        $profil=ucfirst($profils->getLibelle());
        $class="App\Entity\\$profil";
        $user= $this->serializer->denormalize($post_user, $class, 'json');
        // dd($user);
        $user->setProfil($profils);
        $errors = $this->validator->validate($user);
         if ($errors) {
             $errorsString =$this->serializer->serialize($errors,'json');
             return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
         }
        $password = $user->getPassword();
        $user=$user->setPassword($this->encoder->encodePassword($user, $password));
        //dd($user);
        $user->setAvatar($avatar);
        // dd($user);
        $this->manager->persist($user);
        $this->manager->flush();
        fclose($avatar);
        return $user;
    }*/




    public function Depot(Request $request){

        $data = $request->getContent();
        $data = $this->serializer->decode($data, "json");
    }
}