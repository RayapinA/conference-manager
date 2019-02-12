<?php

namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserManager extends AbstractController
{
    private $userRepository;
    private $userDoctrine;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->userDoctrine = $em;
    }

    public function getAllUser(){

        return $this->userRepository->findAll();
    }

    public function save(User $user){

        $this->userDoctrine->persist($user);
        $this->userDoctrine->flush();
    }

}