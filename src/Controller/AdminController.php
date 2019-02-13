<?php

namespace App\Controller;

use App\Manager\ConferenceManager;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        return $this->render('admin/index.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/conference", name="adminConference")
     */
    public function adminConference(ConferenceManager $conferenceManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $conferences = $conferenceManager->getAllConferences();

        return $this->render('admin/adminConference.html.twig', [
            "conferences" => $conferences
        ]);
    }

    /**
     * @Route("/admin/user", name="adminUser")
     */
    public function adminUser(UserManager $userManager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        $users = $userManager->getAllUser();
        return $this->render('admin/adminUser.html.twig', [
            "users" => $users
        ]);
    }


}
