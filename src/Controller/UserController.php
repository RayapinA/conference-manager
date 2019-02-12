<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function showAllUsers(UserManager $userManager)
    {

        $users =  $userManager->getAllUser();

        return $this->render('user/showAllUser.html.twig', [
            "users" => $users
        ]);
    }

    /**
     * @Route("/user/add", name="addUsers")
     */
    public function addUser(Request $request,UserManager $userManager)
    {

        $user =  new User();

        $formAddUser = $this->createForm(UserType::class,$user);
        $formAddUser->handleRequest($request);

        if($formAddUser->isSubmitted() &&  $formAddUser->isValid()){

            $userManager->save($user);
        }

        return $this->render('user/addUser.html.twig', [
            'form' => $formAddUser->createView(),
        ]);

    }

    /**
     * @Route("/user/edit/{id}", name="editUsers")
     */
    public function editUser(Request $request,UserManager $userManager,User $user)
    {


        $formAddUser = $this->createForm(UserType::class,$user);
        $formAddUser->handleRequest($request);

        if($formAddUser->isSubmitted() &&  $formAddUser->isValid()){

            $userManager->save($user);
        }

        return $this->render('user/editUser.html.twig', [
            'form' => $formAddUser->createView(),
        ]);

    }
}
