<?php

namespace App\Manager;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityManager extends AbstractController
{
    private $securityDoctrine;


    public function __construct(EntityManagerInterface $em)
    {
        $this->securityDoctrine = $em;
    }

    public function save(User $user)
    {

        $this->securityDoctrine->persist($user);
        $this->securityDoctrine->flush();

    }
}