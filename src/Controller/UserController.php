<?php

namespace App\Controller;

use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/users", name="users")
     */
    public function showAllUsers(UserManager $userManager){

        $users =  $userManager->getAllUser();

        return $this->render('user/showAllUser.html.twig', [
            "users" => $users
        ]);
    }
}
